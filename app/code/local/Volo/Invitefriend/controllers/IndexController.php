<?php

class Volo_Invitefriend_IndexController extends Mage_Core_Controller_Front_Action
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

	public function sendinviteAction()
	{
		$invite_email = $this->getRequest()->getParam('invite_email_0', false);
		$response=array('result'=>$invite_email);
		$response = Mage::helper('core')->jsonEncode($response);
		$this->getResponse()->setBody($response);
	}
}
?>
