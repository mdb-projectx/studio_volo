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
                Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::helper("core/url")->getCurrentUrl());
                $this->_redirectUrl('/customer/account/login');
        }
    }


	public function sendinviteAction()
	{
		$result=true;
		$emailcounter = $this->getRequest()->getParam('email_counter', false);
		$discount_code = $this->getRequest()->getParam('discount_code', false);
		$invite_message = $this->getRequest()->getParam('invite_message', false);
		
		$firstname= Mage::getSingleton('customer/session')->getCustomer()->getFirstname();
		$lastname= Mage::getSingleton('customer/session')->getCustomer()->getLastname();
		
		$baseurl = str_replace('/index.php/','',Mage::getBaseUrl());
		$invite_message.='<br><a href="'.$baseurl.'invitefriend/index/refer?code='.$discount_code.'">Start Shopping</a>';
		
$invite_html ='
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Kelly just gifted you a $25 voucher at VoloDesign.com</title>
		<style type="text/css">
			/* Client-specific Styles */
			#outlook a{padding:0;} /* Force Outlook to provide a "view in browser" button. */
			body{width:100% !important;} .ReadMsgBody{width:100%;} .ExternalClass{width:100%;} /* Force Hotmail to display emails at full width */
			body{-webkit-text-size-adjust:none;} /* Prevent Webkit platforms from changing default text sizes. */
			/* Reset Styles */
			body{margin:0; padding:0;}
			img{border:0; height:auto; line-height:100%; outline:none; text-decoration:none;}
			table td{border-collapse:collapse;}
		</style>
	</head>
    <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="-webkit-text-size-adjust: none;margin: 0;padding: 0;background: #FFFFFF;width: 100%;">
    	<center>
        	<table border="0" cellpadding="20" cellspacing="0" height="100%" width="100%" id="backgroundTable" style="height: 100%;margin: 0;padding: 0;width: 100%;background: #FFFFFF;text-align: center;">			
				<tr>
					<td align="center" style="border-collapse: collapse;"><table border="0" cellpadding="0" cellspacing="0" width="670" id="bodyTable" style="margin: 0 auto;background: #cccfd1;">
							<tr>
								<td height="20" style="border-collapse: collapse;line-height:0;font-size:0;"><img src="'.$baseurl.'/skin/frontend/default/respond/invitefriend/table-top.png" width="670" height="20" alt="" style="vertical-align:bottom;display:block;border: 0;outline: none;text-decoration: none;"></td>
							</tr>
							<tr>
								<td style="border-collapse: collapse;"><table border="0" cellpadding="0" cellspacing="0" width="670" id="contentTable" style="background: #FFFFFF">
										<tr>
											<td width="20" height="470" style="background: #cccfd1;border-collapse: collapse;line-height:0;font-size:0;"><img src="'.$baseurl.'/skin/frontend/default/respond/invitefriend/table-left.png" width="20" height="470" alt="" style="vertical-align:middle;display:block;outline: none;text-decoration: none;"></td>
											<td width="630" height="470" valign="top" style="border-collapse: collapse;display:block;"><table border="0" cellpadding="0" cellspacing="0" width="630" height="470" id="emailContent" style="background: #FFFFFF;">
													<tr>
														<td valign="top" align="center" height="80" style="border-collapse: collapse;">
															<table border="0" cellpadding="0" cellspacing="0" width="630" height="80" id="contentHead">
																<tr>
																	<td width="630" height="80" align="center" style="border-collapse: collapse;"><a href="http://www.volodesign.com"><img src="'.$baseurl.'/skin/frontend/default/respond/invitefriend/logo.png" width="570" height="80" alt="Volo" style="vertical-align:top;display:block;border: 0;outline: none;text-decoration: none;"></a></td>
																</tr>
															</table>
														</td>
													</tr>														
													<tr>
														<td valign="top" align="center" height="390" style="border-collapse: collapse;"><table border="0" cellpadding="30" cellspacing="0" width="630" height="390" id="contentBody">
																<tr>
																	<td align="left" width="580" height="330" valign="top" style="border-collapse: collapse;display:block;">															
																		<p style="margin: 0;font-family: Lato,\'Lucida Sans Unicode\',\'Lucida Grande\',Arial,Helvetica,sans-serif;font-size: 14px;line-height: 21px;">																		
																			Hi, here\'s a $20 gift voucher for VoloDesign.com. They cut out the middlemen and offer beautiful design at up to 70% off traditional retail. Enter <b>'.$discount_code.'</b> at checkout and you\'ll get $20 off your first order!<br><br>																			
																			<a href="http://www.volodesign.com"><img src="'.$baseurl.'/skin/frontend/default/respond/invitefriend/shop.png" width="170" height="40" alt="Start Shopping" style="border: 0;height: auto;outline: none;text-decoration: none;"></a><br><br>
																			'.$firstname.' '.$lastname.'
																		</p>															
																	</td>
																</tr>
														</table></td>
													</tr>
											</table></td>
											<td width="20" height="470" style="background: #cccfd1;border-collapse: collapse;line-height:0;font-size:0;"><img src="'.$baseurl.'/skin/frontend/default/respond/invitefriend/table-right.png" width="20" height="470" alt="" style="vertical-align:middle;display:block;border: 0;outline: none;text-decoration: none;"></td>
										</tr>
								</table></td>
							</tr>
							<tr>
								<td height="20" style="border-collapse: collapse;line-height:0;font-size:0;"><img src="'.$baseurl.'/skin/frontend/default/respond/invitefriend/table-bottom.png" width="670" height="20" alt="" style="vertical-align:top;display:block;outline: none;text-decoration: none;"></td>
							</tr>
							<tr>
								<td align="center" id="bodyFoot" style="border-collapse: collapse;"></td>
							</tr>
					</table></td>
				</tr>
			</table>
		</center>
	</body>
</html>
';		
		
		for ($i=0; $i<=$emailcounter; $i++)
		{
			$email=$this->getRequest()->getParam('invite_email_'.$i, false);
			if(filter_var($email, FILTER_VALIDATE_EMAIL))
			{
				$service_url = 'http://mandrillapp.com/api/1.0/messages/send.json';
				$curl = curl_init($service_url);
				$curl_post_data = array(
					"key" => "8de2f48d-6932-4918-9af1-ebd722957d68",
					'message' => array(
						"html" => $invite_html,
						"text" => $invite_message,
						"from_email" => "contact@volodesign.com",
						"from_name" =>  $firstname." ".$lastname,
						"subject" => $firstname." ".$lastname." just gifted you a $25 voucher at Volo!",
						"to" => array(array("email" => $email)),
						"track_opens" => true,
						"track_clicks" => true,
						"auto_text" => true,
						"tags"=>array("Invite Friend")
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

//              $response=array('result'=>$invite_email);
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

