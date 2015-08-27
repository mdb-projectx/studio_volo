<?php
class Curebit_Onepage_SuccessController extends Mage_Core_Controller_Front_Action {
    public function indexAction()
    {
        $this->loadLayout();
        $layout = $this->getLayout();        
        $layout->getBlock('root')->setTemplate('page/1column.phtml');
        $this->renderLayout();
    }
    
    public function addActionLayoutHandles()
    {
        parent::addActionLayoutHandles();
        $layout = $this->getLayout();        
        $layout->getUpdate()->addHandle('checkout_onepage_success');
        return $this;
    }
}
