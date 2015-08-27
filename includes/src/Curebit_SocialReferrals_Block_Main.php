<?php
/**
 * General checkout success Curebit integration class
 *
 * @category   Curebit
 * @package    Curebit_SocialReferrals
 */
class Curebit_SocialReferrals_Block_Main extends Mage_Core_Block_Template
{
		const SERVER = 'https://www.curebit.com';
    const DEFAULT_JS_INCLUDE = '/javascripts/api/all-%s.js';
    const DEFAULT_JS_PURCHASE_URL = '/public/%s/purchases/create_magento.js';
    const VERSION_STRING = '0.3';
	/**
     * Retrieve information from configuration
     *
     * @param   string $field
     * @return  mixed
     */
    public function getConfigData($field)
    {
        $path = 'checkout/curebit/'.$field;
        return Mage::getStoreConfig($path);
    }
    
    public function isEnabled()
    {
        return ($this->getConfigData('enabled') && $this->getSiteId() != "" ? true : false);
    }

    public function getSiteId()
    {
        return trim($this->getConfigData('site_id'));
    }

    public function isDebug()
    {
        return ($this->getConfigData('debug'));
    }
    
    public function getCustomerId()
    {
        return Mage::getSingleton('customer/session')->getCustomerId();
    }
    
    public function getCurebitApiVersion()
    {
        return self::VERSION_STRING;
    }

    public function getCurebitJsUrl()
    {
        return sprintf(self::SERVER . self::DEFAULT_JS_PURCHASE_URL, $this->getSiteId());
    }

    public function getJSCodeByOrderId($orderId, $byIncrementId = false)
    {
        if ($byIncrementId) {
          $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
        } else {
          $order = Mage::getModel('sales/order')->load($orderId);                
        }      
        if (!$order->getId()) {
            return $this->error('Main.php#getJSCode, order ' . $orderId . ' could not be found!');
        }
        return $this->getJSCode($order);
    } 
   
    public function getJSCode($order)
    {
        try {         

            $this->log('Main.php#getJSCode for order number: ' . $order->getIncrementId());
            /* @var $order Mage_Sales_Model_Order */
            
            $curebitI = 0;
            $curebitItems = '';
            foreach ($order->getAllItems() as $curebitItem) {
              $curebitItems .= sprintf('p[i][%d][product_id]=%s&', $curebitI, urlencode($curebitItem->getProductId()));
              $curebitItems .= sprintf('p[i][%d][price]=%.2f&', $curebitI, urlencode($curebitItem->getPrice()));
              $curebitItems .= sprintf('p[i][%d][quantity]=%d&', $curebitI, urlencode($curebitItem->getQtyOrdered()));
              $curebitI++;
            }
            $curebitItems = substr($curebitItems, 0, -1);
            
            $res = '<script type="text/javascript" src=\''.$this->getCurebitJsUrl().
                    '?v='.urlencode($this->getCurebitApiVersion()).
                    '&p[customer_id]='.urlencode($this->getCustomerId()).
                    '&p[order_number]='.$order->getIncrementId().
                    '&p[subtotal]='.urlencode(sprintf("%.2f", $order->getSubtotal())).
                    '&p[order_date]='.urlencode($order->getCreatedAt()).
                    '&p[customer_email]='.urlencode($order->getCustomerEmail()).
                    '&p[coupon_code]='.urlencode($order->getCouponCode()).                
                    '&'.$curebitItems.'\'></script>';
            return $res;
        } catch (Exception $e) {
            return $this->error('Main.php#getJSCode, Order Id '. $order->getIncrementId()  . ' Exception: ' . $e->getMessage());
            
        }
    }
    private function error($message) {
        $this->log($message);
        return '<!-- Curebit Error: ' . $message . '-->';
    }
    private function log($message) {
        Mage::log($message, null, 'curebit-socialreferrals.log');
    }
}
