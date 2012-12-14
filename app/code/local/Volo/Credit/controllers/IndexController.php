<?php

class Volo_Credit_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        if ( Mage::getSingleton('customer/session')->isLoggedIn())
        {
                $this->loadLayout(array('default'));
                $this->renderLayout();
        }
        else
        {
                $this->_redirectUrl('/customer/account/login');
        }
    }
}

?>
