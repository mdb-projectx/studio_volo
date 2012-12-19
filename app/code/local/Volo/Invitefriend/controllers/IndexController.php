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
				$firstname= Mage::getSingleton('customer/session')->getCustomer()->getFirstname();
				$lastname= Mage::getSingleton('customer/session')->getCustomer()->getLastname();


	$service_url = 'http://mandrillapp.com/api/1.0/messages/send.json';
    $curl = curl_init($service_url);
    $curl_post_data = array(
        "key" => "8de2f48d-6932-4918-9af1-ebd722957d68",
		'message' => array(
			"html" => $invite_message,
			"text" => $invite_message,
			"from_email" => "contact@volodesign.com",
			"from_name" => "Volodesign",
			"subject" => $firstname." ".$lastname." just gifted you a $20 voucher at Volo!",
			"to" => array(array("email" => $email)),
			"track_opens" => true,
			"track_clicks" => true,
			"auto_text" => true
		)
    );
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($curl_post_data));  
	curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, TRUE);
	$curl_response = curl_exec($curl);
	curl_close($curl);
	if (!$curl_response)
	{
		$result=false;
	}
			}
		}

//		$response=array('result'=>$invite_email);
$response=array('result'=>$result);
		$response = Mage::helper('core')->jsonEncode($response);
		$this->getResponse()->setBody($response);
	}

	public function referAction()
	{
		$coupon_code = $this->getRequest()->getParam('code', false);
		if ($coupon_code != '') 
		{
			Mage::getSingleton("checkout/session")->setData("coupon_code",$coupon_code);
			Mage::getSingleton('checkout/cart')->getQuote()->setCouponCode($coupon_code)->save();
			Mage::getSingleton('core/session')->addSuccess($this->__('Coupon was automatically applied'));
    		}
		 $this->_redirectUrl('/');
	}
}
?>
