<?php

/**
 * Multishipping checkout success Curebit integration
 *
 * @category   Curebit
 * @package    Curebit_SocialReferrals
 */
class Curebit_SocialReferrals_Block_Multishipping_Success extends Curebit_SocialReferrals_Block_Main
{
    
    protected function _construct()
    {
        $this->setTemplate('curebit/multishipping/success.phtml');
        parent::_construct();
    }
    
    public function getOrderIds()
    {
        $ids = $this->getData('order_ids');
        if ($ids && is_array($ids)) {
            return $ids;
        }
        return false;
    }
}
