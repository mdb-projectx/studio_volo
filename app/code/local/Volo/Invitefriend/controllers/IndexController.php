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
		$result=true;
		$emailcounter = $this->getRequest()->getParam('email_counter', false);
		$invite_message = $this->getRequest()->getParam('invite_message', false);
		for ($i=0; $i<=$emailcounter; $i++)
		{
			$email=$this->getRequest()->getParam('invite_email_'.$i, false);
			if(filter_var($email, FILTER_VALIDATE_EMAIL))
			{
				$mail = Mage::getModel('core/email');
                                $mail->setToEmail($email);
                                $mail->setBody($invite_message);
                                $mail->setSubject('Volodesign');
                                $mail->setFromEmail('voloproject@gmail.com');
                                $mail->setFromName('Volodesign');
                                $mail->setType('html');// YOu can use Html or text as Mail format

                                try {
                                        $mail->send();
                //Mage::getSingleton('core/session')->addSuccess('Your request has been sent');
                                }
                                catch (Exception $e) {
                //Mage::getSingleton('core/session')->addError('Unable to send.');
                                        $result=false;
                                }

			}
		}

//		$response=array('result'=>$invite_email);
$response=array('result'=>$result);
		$response = Mage::helper('core')->jsonEncode($response);
		$this->getResponse()->setBody($response);
	}
}
?>
