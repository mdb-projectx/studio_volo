<?php
class Curebit_SocialReferrals_AddController extends Mage_Core_Controller_Front_Action {
    public function indexAction()
    {
        try
        {
            $message = Mage::getModel('curebit_socialreferrals/add')
            ->addCoupon($this->getRequest());
        }
        catch(Mage_Core_Exception $e)
        {
            $message = $e->getMessage();
        }
        
        $this->loadLayout();
        $content = $this->getLayout()->getBlock('content');
        $content->append(
            $this->getLayout()->createBlock('core/text')
            ->setText($message)
        );
        $this->renderLayout();
    }
}
