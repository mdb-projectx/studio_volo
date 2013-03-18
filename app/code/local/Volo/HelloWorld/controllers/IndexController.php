<?php

class Volo_HelloWorld_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
	if ( Mage::getSingleton('customer/session')->isLoggedIn())
        {
		$this->loadLayout(array('default'));
                $this->renderLayout();
		//$this->_redirectUrl('/invite-friends-splash');
        }
        else
        {
                Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::helper("core/url")->getCurrentUrl());
                $this->_redirectUrl('/customer/account/login');
        }
    }
}
