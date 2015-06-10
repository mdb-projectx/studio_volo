<?php
 
class Mdb_Tracking_Model_Observer
{
        
    public function importTracking()
    {
        
        $path="/srv/www/volo/public_html/googlefeed/VoloTracking/";
        $date = date('Y-m-d', time() - 86400);
		$filename='Output '.$date.'.xlsx';
        
		// Include PHPExcel_IOFactory 
        require_once '/srv/www/volo/cronjob/php/PHPExcel1.8/PHPExcel/IOFactory.php';
		
		if (!file_exists($path.$filename)) {
            exit("file not found" . EOL);
        } 

        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel = $objReader->load($path.$filename);

        $sheetNames= $objPHPExcel->getSheetNames();
		$sheetindex='0';
        $sheet = $objPHPExcel->getSheet($sheetindex);
        $startRow=2;
        $highestRow = $sheet->getHighestRow();

		$data=array();
        for ($row = $startRow; $row <= $highestRow; $row++)
        {
			if ((trim($sheet->getCell('A'.$row)->getValue()) == 'VLCP01' || trim($sheet->getCell('A'.$row)->getValue()) == 'VLCP10') && trim($sheet->getCell('E'.$row)->getValue()) != '1.FREIGHT' && trim($sheet->getCell('E'.$row)->getValue()) != '1.SALESTAX' && intval($sheet->getCell('F'.$row)->getValue()) > 0)
			{
				$custnum=trim($sheet->getCell('A'.$row)->getValue());
				$ponum=str_replace('PO','',trim($sheet->getCell('B'.$row)->getValue()))+0;
				$orderdate=trim($sheet->getCell('C'.$row)->getValue());
				$shiptoname=trim($sheet->getCell('D'.$row)->getValue());
				$itemnum=trim($sheet->getCell('E'.$row)->getValue());
				$qty=intval($sheet->getCell('F'.$row)->getValue());
				$shippingagentcode=trim($sheet->getCell('G'.$row)->getValue());
				$trackingnum=trim($sheet->getCell('H'.$row)->getValue());
				$shipfrom=trim($sheet->getCell('I'.$row)->getValue());
				
				$data[$ponum][]=array('sku'=>$itemnum, 'qty'=>$qty, 'carrierCode'=>$shippingagentcode, 'trackingNumber'=>$trackingnum);
			} 
		}
        
		foreach ($data as $key => $value)
		{
            $orderIncrementId = intval($key);
            
            $shipmentItems = array();
			foreach ($value as $shipment)
			{
                $carrierTitle = '';
                switch($shipment['carrierCode']) {
                    case 'FEDEX':
                        $carrierTitle = 'Federal Express';
                        break;
                    case 'USPS':
                        $carrierTitle = 'United States Postal Service';
                        break;
                    case 'UPS':
                        $carrierTitle = 'United Parcel Service';
                        break;
                    case 'EFW':
                        $carrierTitle = 'Estes Freight Forwarding';
                        break;
                    case 'SUN':
                        // Sun has no tracking numbers
                        $carrierTitle = 'Sun Delivery';
                        $shipment['trackingNumber'] = '*see shipment comments';
                        break;
                    case 'MDBDEL':
                        $carrierTitle = 'MDB Delivery';
                        break;
                }
                
                $shipmentItems[] = array(
                    'sku' => $shipment['sku'],
                    'qty' => $shipment['qty'],
                    'carrierCode' => $shipment['carrierCode'],
                    'carrierTitle' => $carrierTitle,
                    'trackingNumber' => $shipment['trackingNumber']
                );
            }

            // Note to customers
            $customerEmailComments = 'To track shipments made by Estes Forwarding Worldwide, go to <a href="https://www.efwnow.com">efwnow.com</a>, enter your waybill number, and click on Rapid Track. Please note that the website is showing an estimated date. You will receive a phone call from the white glove directly to schedule an appointment.<br><br>To track shipments made by Sun Delivery, please visit <a href="https://mysundelivery.com/public/ShipmentTracking.aspx">mysundelivery.com/public/ShipmentTracking.aspx</a> and enter your zip code, last name and street number.';

            $order = Mage::getModel('sales/order')
                     ->loadByIncrementId($orderIncrementId);
 
            if (!$order->getId()) {
                // Mage::throwException("Order does not exist");
            } else {
                if ($order->canShip()) {
                    try {

                        $shipment = Mage::getModel('sales/service_order', $order)
                                    ->prepareShipment($this->_getItemQtys($order, $shipmentItems));

                        foreach($shipmentItems as $shipmentItem) {
                            $arrTracking = array(
                                'carrier_code' => isset($shipmentItem['carrierCode']) ? $shipmentItem['carrierCode'] : $order->getShippingCarrier()->getCarrierCode(),
                                'title' => isset($shipmentItem['carrierTitle']) ? $shipmentItem['carrierTitle'] : $order->getShippingCarrier()->getConfigData('title'),
                                'number' => $shipmentItem['trackingNumber'],
                            );

                            $track = Mage::getModel('sales/order_shipment_track')->addData($arrTracking);
                            $shipment->addTrack($track);
                        }

                        // Register Shipment
                        $shipment->register();

                        // Save the Shipment
                        $this->_saveShipment($shipment, $order, $customerEmailComments);

                        // Finally, Save the Order
                        $this->_saveOrder($order);
                    } catch (Exception $e) {

                        throw $e;
                    }
                }
            }
        }
    }

    /**
     * Get the Quantities shipped for the Order, based on an item-level
     * This method can also be modified, to have the Partial Shipment functionality in place
     *
     * @param $order Mage_Sales_Model_Order
     * @return array
     */
    protected function _getItemQtys(Mage_Sales_Model_Order $order, $shipmentItems)
    {
        $qty = array();

        foreach ($order->getAllItems() as $_eachItem) {
            /*
            if ($_eachItem->getParentItemId()) {
                $qty[$_eachItem->getParentItemId()] = $_eachItem->getQtyOrdered();
            } else {
                $qty[$_eachItem->getId()] = $_eachItem->getQtyOrdered();
            }
            */
            

            $itemSku = $_eachItem->getSku();
            $itemQty = $_eachItem->getQtyOrdered();

            foreach($shipmentItems as $shipmentItem) {
                if($itemSku == $shipmentItem['sku'] && $itemQty >= $shipmentItem['qty'] && $shipmentItem['qty'] > 0) {
                    if ($_eachItem->getParentItemId()) {
                        $qty[$_eachItem->getParentItemId()] = $shipmentItem['qty'];
                    } else {
                        $qty[$_eachItem->getId()] = $shipmentItem['qty'];
                    }
                }
            }

        }

        return $qty;
    }

    /**
     * Saves the Shipment changes in the Order
     *
     * @param $shipment Mage_Sales_Model_Order_Shipment
     * @param $order Mage_Sales_Model_Order
     * @param $customerEmailComments string
     */
    protected function _saveShipment(Mage_Sales_Model_Order_Shipment $shipment, Mage_Sales_Model_Order $order, $customerEmailComments = '')
    {
        $shipment->getOrder()->setIsInProcess(true);
        $transactionSave = Mage::getModel('core/resource_transaction')
                               ->addObject($shipment)
                               ->addObject($order)
                               ->save();

        // $emailSentStatus = $shipment->getData('email_sent');
        $emailSentStatus = $shipment->getEmailSent();
        
        
            $shipment->sendEmail(true, $customerEmailComments);
            $shipment->setEmailSent(true);
        /*
        if (!is_null($customerEmail) && !$emailSentStatus) {
            $shipment->sendEmail(true, $customerEmailComments);
            $shipment->setEmailSent(true);
            
            $shipment->getOrder()->setIsVisibleOnFront(true)
                >setIsCustomerNotified(true);
        }
        */

        return $this;
    }

    /**
     * Saves the Order, to complete the full life-cycle of the Order
     * Order status will now show as Complete
     *
     * @param $order Mage_Sales_Model_Order
     */
    protected function _saveOrder(Mage_Sales_Model_Order $order)
    {
        
        $total_ordered_items = $order->getData('total_qty_ordered');
        $num_of_shipped_items = 0;

        foreach ($order->getAllVisibleItems() as $item){
           //$item->getQtyOrdered() // Number of item ordered
           $num_of_shipped_items += $item->getQtyShipped();  
           //$item->getQtyInvoiced()
        }

        if($num_of_shipped_items == $total_ordered_items){
            // set status to complete
            $order->setData('state', Mage_Sales_Model_Order::STATE_COMPLETE);
            $order->setData('status', Mage_Sales_Model_Order::STATE_COMPLETE);
        }
        else{
            // Partial 
        }        

        $order->save();

        return $this;
    }
    
}
 
?>
