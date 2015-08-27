<?php
class Curebit_Onepage_Model_Checkout_Session extends Mage_Checkout_Model_Session
{

    public function getLastOrderId()
    {
        $ret = parent::getLastOrderId();
        if(!$ret)
        {
            $c = Mage::getModel('sales/order')->getCollection()->getFirstItem();
            if($c->getIncrementId())
            {
                $ret = $c->getId();
                $block = Mage::getSingleton('core/layout')
                ->createBlock('core/text')->setText('<h1>No last order found, using random system order.</h1>');
                echo $block->toHtml();
            }
        }
        return $ret;
    }
}