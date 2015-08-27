<?php
/**
 * Checkout success Curebit integration
 *
 * @category   Curebit
 * @package    Curebit_SocialReferrals
 */

class Curebit_SocialReferrals_Block_Success extends Curebit_SocialReferrals_Block_Main
{
    private $_order = null;
    
    protected function _construct()
    {
        $this->setTemplate('curebit/success.phtml');
        parent::_construct();
    }
    
    public function getOrder()
    {
        if ($this->_order === null) {
            $orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
            if ($orderId) {
                $order = Mage::getModel('sales/order')->load($orderId);
                if ($order->getId()) {
                    $this->_order = $order;
                }
            }
        }
        return $this->_order;
    }

    public function getOrderId()
    {
        return $this->getOrder()->getIncrementId();
    }
}
