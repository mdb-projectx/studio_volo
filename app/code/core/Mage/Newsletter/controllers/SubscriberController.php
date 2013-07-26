<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Newsletter
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Newsletter subscribe controller
 *
 * @category    Mage
 * @package     Mage_Newsletter
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Newsletter_SubscriberController extends Mage_Core_Controller_Front_Action
{
    /**
      * New subscription action
      */
    public function newAction()
    {
        if ($this->getRequest()->isPost() && $this->getRequest()->getPost('email')) {
            $session            = Mage::getSingleton('core/session');
            $customerSession    = Mage::getSingleton('customer/session');
            $email              = (string) $this->getRequest()->getPost('email');

            try {

                if (!Zend_Validate::is($email, 'EmailAddress')) {
                    Mage::throwException($this->__('Please enter a valid email address.'));
                }

                if (Mage::getStoreConfig(Mage_Newsletter_Model_Subscriber::XML_PATH_ALLOW_GUEST_SUBSCRIBE_FLAG) != 1 && 
                    !$customerSession->isLoggedIn()) {
                    Mage::throwException($this->__('Sorry, but administrator denied subscription for guests. Please <a href="%s">register</a>.', Mage::helper('customer')->getRegisterUrl()));
                }

                $ownerId = Mage::getModel('customer/customer')
                        ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                        ->loadByEmail($email)
                        ->getId();

                if ($ownerId !== null && $ownerId != $customerSession->getId()) {
                    Mage::throwException($this->__('This email address is already assigned to another user.'));
                }

                $status = Mage::getModel('newsletter/subscriber')->subscribe($email);
                if ($status == Mage_Newsletter_Model_Subscriber::STATUS_NOT_ACTIVE) {
			Mage::getModel('core/cookie')->set('Subscribed', true);
                    $session->addSuccess($this->__('Confirmation request has been sent.'));
                }
                else {
/**********
Edwin : Add welcome letter for newsletter sigip
***********************/

$baseurl = str_replace('/index.php/','',Mage::getBaseUrl());
                $invite_message.='<br><a href="'.$baseurl.'invitefriend/index/refer?code='.$discount_code.'">Start Shopping</a>';

$invite_html ='
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Welcome to VOLO</title>
	</head>
    <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="-webkit-text-size-adjust: none;margin: 0;padding: 0;background: #FFFFFF;width: 100%;">
    	<center>
        	<table border="0" cellpadding="20" cellspacing="0" height="100%" width="100%" id="backgroundTable" style="height: 100%;margin: 0;padding: 0;width: 100%;background: #FFFFFF;text-align: center;">			
				<tr>
					<td align="center" style="border-collapse: collapse;"><table border="0" cellpadding="0" cellspacing="0" width="670" id="bodyTable" style="margin: 0 auto;background: #cccfd1;">
							<tr>
								<td height="20" style="border-collapse: collapse;line-height:0;font-size:0;"><img src="'.$baseurl.'/skin/frontend/default/respond/invitefriend/table-top.png" width="670" height="20" alt="" style="vertical-align: bottom;display: block;border: 0;outline: none;text-decoration: none;height: auto;line-height: 100%;"></td>
							</tr>
							<tr>
								<td style="border-collapse: collapse;"><table border="0" cellpadding="0" cellspacing="0" width="670" id="contentTable" style="background: #FFFFFF">
										<tr>
											<td width="20" valign="top" style="background: #cccfd1;border-collapse: collapse;line-height:0;font-size:0;"><img src="'.$baseurl.'/skin/frontend/default/respond/invitefriend/table-left.png" width="20" alt="" style="vertical-align: middle;display: block;outline: none;text-decoration: none;border: 0;height: auto;line-height: 100%;"></td>
											<td width="630" valign="top" style="border-collapse: collapse;display:block;"><table border="0" cellpadding="0" cellspacing="0" width="570" id="emailContent" style="background: #FFFFFF;">
													<tr>
														<td valign="top" align="center" height="80" style="border-collapse: collapse;">
															<table border="0" cellpadding="0" cellspacing="0" height="80" id="contentHead">
																<tr>
																	<td height="80" align="center" style="border-collapse: collapse;"><a href="'.$baseurl.'"><img src="'.$baseurl.'/skin/frontend/default/respond/invitefriend/logo.png" width="570" height="80" alt="Volo" style="vertical-align: top;display: block;border: 0;outline: none;text-decoration: none;height: auto;line-height: 100%;"></a></td>
																</tr>
															</table>
														</td>
													</tr>														
													<tr>
														<td valign="top" align="center" style="border-collapse: collapse;"><table border="0" cellpadding="30" cellspacing="0" width="630" id="contentBody">
															
														<tr><td style="border-collapse: collapse;"><table border="0" cellpadding="0" cellspacing="0">	
														
					<tr>
						<td align="center" valign="top" style="border-collapse: collapse;">
							<!-- // Begin Template Intro \ -->
							<table border="0" cellpadding="0" cellspacing="0" width="570" id="templateIntro">
								<tr>
									<td align="left" valign="top" class="introContent" style="padding: 0 0 0 0;border-collapse: collapse;background: #ffffff;"><a href="'.$baseurl.'"><img src="'.$baseurl.'/skin/frontend/default/respond/newsletter/welcome.jpg" width="570" height="356" alt="Welcome to VOLO" style="vertical-align: middle;display: block;border: 0;outline: none;text-decoration: none;height: auto;line-height: 100%;"></a></td>
								</tr>
							</table>
							<!-- // End Template Intro \ -->
						</td>
					</tr>
					<tr>
						<td align="center" valign="top" style="border-collapse: collapse;">
							<!-- // Begin Template Body \ -->
							<table border="0" cellpadding="0" cellspacing="0" width="570" id="templateBody">
								<tr>
									<td valign="top" class="bodyContent"  style="padding: 16px 0 23px 0;border-collapse: collapse;">
										
										<!-- // Begin Module: Standard Content \ -->
                                                                                <table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td valign="top" style="padding: 0 25px 0 25px;border-collapse: collapse;">
                                                                                <p style="margin-bottom: 19px;color: #212121;font-size: 17px;font-weight: bold;line-height: 19px;font-family: Verdana, sans-serif;">Hello there,</p>
                                                                                <p style="margin-bottom: 19px;color: #212121;font-size: 12px;line-height: 19px;font-family: Verdana, sans-serif;">We&#39;re thrilled you&#39;ve joined us in offering great furniture design at affordable prices.</p>
                                                                                <p style="margin-bottom: 19px;color: #212121;font-size: 12px;line-height: 19px;font-family: Verdana, sans-serif;">So many costs are hidden in the over-priced furniture you buy in traditional retail. We&#39;re transforming how furniture is sold and how you buy it.</p>
                                                                                <p style="margin-bottom: 19px;color: #212121;font-size: 12px;line-height: 19px;font-family: Verdana, sans-serif;">We&#39;re connecting you with the designers directly, saving you up to 70% off traditional retail prices.</p>
                                                                                <p style="margin-bottom: 19px;color: #212121;font-size: 12px;line-height: 19px;font-family: Verdana, sans-serif;">We look forward to seeing you soon.</p>
                                                                                <p style="margin-bottom: 19px;color: #212121;font-size: 12px;line-height: 19px;font-family: Verdana, sans-serif;">To make sure you&#39;re the first to hear when we release a new range of products, make sure you add <a href="mailto:contact@volodesign.com" style="color: #212121;text-decoration: underline;">contact@volodesign.com</a> to your address book.</p>
                                                                                <p style="margin-bottom: 19px;color: #212121;font-size: 12px;line-height: 19px;font-family: Verdana, sans-serif;">The VOLO Design Team</p>
                                                                                </td></tr></table>
                                                                                <!-- // End Module: Standard Content \ -->
										
									</td>
									<td valign="top" width="204" style="padding: 23px 0 35px 0;border-collapse: collapse;"><a href="'.$baseurl.'"><img src="'.$baseurl.'/skin/frontend/default/respond/newsletter/gift.png" width="204" height="491" alt="Start Shopping" style="vertical-align: top;display: block;border: 0;outline: none;text-decoration: none;height: auto;line-height: 100%;"></a>
									</td>
								</tr>
							</table>
							<!-- // End Template Body \ -->
						</td>
					</tr>
					<tr>
						<td align="center" valign="top" style="border-collapse: collapse;border-top: 1px solid #e6e6e6;">
							<!-- // Begin Template Links \ -->
							<table border="0" cellpadding="0" cellspacing="0" width="570" id="templateLinks">
								<tr>
									<td align="left" valign="top" class="linksContent" style="border-collapse: collapse;padding: 21px 0 0 0;">
										
										<table border="0" cellpadding="0" cellspacing="0" width="100%">
											<tr>
												<td align="center" style="border-collapse: collapse;margin: 0;padding: 0;font-family: Arial, Helvetica, sans-serif;font-weight: normal;text-transform: uppercase;font-size: 11px;line-height: 16px;-webkit-text-size-adjust:none;"><a href="'.$baseurl.'/sofas.html" style="color: #888;text-decoration: none;">Sofas</a></td>
												<td align="center" style="border-collapse: collapse;margin: 0;padding: 0;font-family: Arial, Helvetica, sans-serif;font-weight: normal;text-transform: uppercase;font-size: 11px;line-height: 16px;-webkit-text-size-adjust:none;"><a href="'.$baseurl.'/chairs.html" style="color: #888;text-decoration: none;">Chairs</a></td>
												<td align="center" style="border-collapse: collapse;margin: 0;padding: 0;font-family: Arial, Helvetica, sans-serif;font-weight: normal;text-transform: uppercase;font-size: 11px;line-height: 16px;-webkit-text-size-adjust:none;"><a href="'.$baseurl.'/tables.html" style="color: #888;text-decoration: none;">Tables</a></td>
												<td align="center" style="border-collapse: collapse;margin: 0;padding: 0;font-family: Arial, Helvetica, sans-serif;font-weight: normal;text-transform: uppercase;font-size: 11px;line-height: 16px;-webkit-text-size-adjust:none;"><a href="'.$baseurl.'/lighting.html" style="color: #888;text-decoration: none;">Lighting</a></td>
												<td align="center" style="border-collapse: collapse;margin: 0;padding: 0;font-family: Arial, Helvetica, sans-serif;font-weight: normal;text-transform: uppercase;font-size: 11px;line-height: 16px;-webkit-text-size-adjust:none;"><a href="'.$baseurl.'/leisure.html" style="color: #888;text-decoration: none;">Leisure</a></td>
												<td align="center" style="border-collapse: collapse;margin: 0;padding: 0;font-family: Arial, Helvetica, sans-serif;font-weight: normal;text-transform: uppercase;font-size: 11px;line-height: 16px;-webkit-text-size-adjust:none;"><a href="'.$baseurl.'/designers/" style="color: #888;text-decoration: none;">Designers</a></td>
												<td align="center" style="border-collapse: collapse;margin: 0;padding: 0;font-family: Arial, Helvetica, sans-serif;font-weight: normal;text-transform: uppercase;font-size: 11px;line-height: 16px;-webkit-text-size-adjust:none;"><a href="http://volo-design.tumblr.com" style="color: #888;text-decoration: none;">The Journal</a></td>
												<td align="center" style="border-collapse: collapse;margin: 0;padding: 0;font-family: Arial, Helvetica, sans-serif;font-weight: normal;text-transform: uppercase;font-size: 11px;line-height: 16px;-webkit-text-size-adjust:none;"><a href="'.$baseurl.'/about/" style="color: #888;text-decoration: none;">About Us</a></td>
											</tr>
										</table>

									</td>
								</tr>
							</table>
							<!-- // End Template Links \ -->
						</td>
					</tr>
					
					
					</table></td></tr>
					
					
														</table></td>
													</tr>
											</table></td>
											<td valign="top" width="20" height="470" style="background: #cccfd1;border-collapse: collapse;line-height:0;font-size:0;"><img src="'.$baseurl.'/skin/frontend/default/respond/invitefriend/table-right.png" width="20" height="470" alt="" style="vertical-align: middle;display: block;border: 0;outline: none;text-decoration: none;height: auto;line-height: 100%;"></td>
										</tr>
								</table></td>
							</tr>
							<tr>
								<td height="20" style="border-collapse: collapse;line-height:0;font-size:0;"><img src="'.$baseurl.'/skin/frontend/default/respond/invitefriend/table-bottom.png" width="670" height="20" alt="" style="vertical-align: top;display: block;outline: none;text-decoration: none;border: 0;height: auto;line-height: 100%;"></td>
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

$invite_message='
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Welcome to VOLO</title>
	</head>
    <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="-webkit-text-size-adjust: none;margin: 0;padding: 0;background: #FFFFFF;width: 100%;">
    	<center>
        	<table border="0" cellpadding="20" cellspacing="0" height="100%" width="100%" id="backgroundTable" style="height: 100%;margin: 0;padding: 0;width: 100%;background: #FFFFFF;text-align: center;">			
				<tr>
					<td align="center" style="border-collapse: collapse;"><table border="0" cellpadding="0" cellspacing="0" width="670" id="bodyTable" style="margin: 0 auto;background: #cccfd1;">
							<tr>
								<td height="20" style="border-collapse: collapse;line-height:0;font-size:0;"><img src="'.$baseurl.'/skin/frontend/default/respond/invitefriend/table-top.png" width="670" height="20" alt="" style="vertical-align: bottom;display: block;border: 0;outline: none;text-decoration: none;height: auto;line-height: 100%;"></td>
							</tr>
							<tr>
								<td style="border-collapse: collapse;"><table border="0" cellpadding="0" cellspacing="0" width="670" id="contentTable" style="background: #FFFFFF">
										<tr>
											<td width="20" valign="top" style="background: #cccfd1;border-collapse: collapse;line-height:0;font-size:0;"><img src="'.$baseurl.'/skin/frontend/default/respond/invitefriend/table-left.png" width="20" alt="" style="vertical-align: middle;display: block;outline: none;text-decoration: none;border: 0;height: auto;line-height: 100%;"></td>
											<td width="630" valign="top" style="border-collapse: collapse;display:block;"><table border="0" cellpadding="0" cellspacing="0" width="570" id="emailContent" style="background: #FFFFFF;">
													<tr>
														<td valign="top" align="center" height="80" style="border-collapse: collapse;">
															<table border="0" cellpadding="0" cellspacing="0" height="80" id="contentHead">
																<tr>
																	<td height="80" align="center" style="border-collapse: collapse;"><a href="'.$baseurl.'"><img src="'.$baseurl.'/skin/frontend/default/respond/invitefriend/logo.png" width="570" height="80" alt="Volo" style="vertical-align: top;display: block;border: 0;outline: none;text-decoration: none;height: auto;line-height: 100%;"></a></td>
																</tr>
															</table>
														</td>
													</tr>														
													<tr>
														<td valign="top" align="center" style="border-collapse: collapse;"><table border="0" cellpadding="30" cellspacing="0" width="630" id="contentBody">
															
														<tr><td style="border-collapse: collapse;"><table border="0" cellpadding="0" cellspacing="0">	
														
					<tr>
						<td align="center" valign="top" style="border-collapse: collapse;">
							<!-- // Begin Template Intro \ -->
							<table border="0" cellpadding="0" cellspacing="0" width="570" id="templateIntro">
								<tr>
									<td align="left" valign="top" class="introContent" style="padding: 0 0 0 0;border-collapse: collapse;background: #ffffff;"><a href="'.$baseurl.'"><img src="'.$baseurl.'/skin/frontend/default/respond/newsletter/welcome.jpg" width="570" height="356" alt="Welcome to VOLO" style="vertical-align: middle;display: block;border: 0;outline: none;text-decoration: none;height: auto;line-height: 100%;"></a></td>
								</tr>
							</table>
							<!-- // End Template Intro \ -->
						</td>
					</tr>
					<tr>
						<td align="center" valign="top" style="border-collapse: collapse;">
							<!-- // Begin Template Body \ -->
							<table border="0" cellpadding="0" cellspacing="0" width="570" id="templateBody">
								<tr>
									<td valign="top" class="bodyContent"  style="padding: 16px 0 23px 0;border-collapse: collapse;">
						
										<!-- // Begin Module: Standard Content \ -->
										<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td valign="top" style="padding: 0 25px 0 25px;border-collapse: collapse;">
										<p style="margin-bottom: 19px;color: #212121;font-size: 17px;font-weight: bold;line-height: 19px;font-family: Verdana, sans-serif;">Hello there,</p>
										<p style="margin-bottom: 19px;color: #212121;font-size: 12px;line-height: 19px;font-family: Verdana, sans-serif;">We&#39;re thrilled you&#39;ve joined us in offering great furniture design at affordable prices.</p>
										<p style="margin-bottom: 19px;color: #212121;font-size: 12px;line-height: 19px;font-family: Verdana, sans-serif;">So many costs are hidden in the over-priced furniture you buy in traditional retail. We&#39;re transforming how furniture is sold and how you buy it.</p>
										<p style="margin-bottom: 19px;color: #212121;font-size: 12px;line-height: 19px;font-family: Verdana, sans-serif;">We&#39;re connecting you with the designers directly, saving you up to 70% off traditional retail prices.</p>
										<p style="margin-bottom: 19px;color: #212121;font-size: 12px;line-height: 19px;font-family: Verdana, sans-serif;">We look forward to seeing you soon.</p>
										<p style="margin-bottom: 19px;color: #212121;font-size: 12px;line-height: 19px;font-family: Verdana, sans-serif;">To make sure you&#39;re the first to hear when we release a new range of products, make sure you add <a href="mailto:contact@volodesign.com" style="color: #212121;text-decoration: underline;">contact@volodesign.com</a> to your address book.</p>
										<p style="margin-bottom: 19px;color: #212121;font-size: 12px;line-height: 19px;font-family: Verdana, sans-serif;">The VOLO Design Team</p>
										</td></tr></table>										
										<!-- // End Module: Standard Content \ -->
										
									</td>
									<td valign="top" width="204" style="padding: 23px 0 35px 0;border-collapse: collapse;"><a href="'.$baseurl.'"><img src="'.$baseurl.'/skin/frontend/default/respond/newsletter/gift.png" width="204" height="491" alt="Start Shopping" style="vertical-align: top;display: block;border: 0;outline: none;text-decoration: none;height: auto;line-height: 100%;"></a>
									</td>
								</tr>
							</table>
							<!-- // End Template Body \ -->
						</td>
					</tr>
					<tr>
						<td align="center" valign="top" style="border-collapse: collapse;border-top: 1px solid #e6e6e6;">
							<!-- // Begin Template Links \ -->
							<table border="0" cellpadding="0" cellspacing="0" width="570" id="templateLinks">
								<tr>
									<td align="left" valign="top" class="linksContent" style="border-collapse: collapse;padding: 21px 0 0 0;">
										
										<table border="0" cellpadding="0" cellspacing="0" width="100%">
											<tr>
												<td align="center" style="border-collapse: collapse;margin: 0;padding: 0;font-family: Arial, Helvetica, sans-serif;font-weight: normal;text-transform: uppercase;font-size: 11px;line-height: 16px;-webkit-text-size-adjust:none;"><a href="'.$baseurl.'/sofas.html" style="color: #888;text-decoration: none;">Sofas</a></td>
												<td align="center" style="border-collapse: collapse;margin: 0;padding: 0;font-family: Arial, Helvetica, sans-serif;font-weight: normal;text-transform: uppercase;font-size: 11px;line-height: 16px;-webkit-text-size-adjust:none;"><a href="'.$baseurl.'/chairs.html" style="color: #888;text-decoration: none;">Chairs</a></td>
												<td align="center" style="border-collapse: collapse;margin: 0;padding: 0;font-family: Arial, Helvetica, sans-serif;font-weight: normal;text-transform: uppercase;font-size: 11px;line-height: 16px;-webkit-text-size-adjust:none;"><a href="'.$baseurl.'/tables.html" style="color: #888;text-decoration: none;">Tables</a></td>
												<td align="center" style="border-collapse: collapse;margin: 0;padding: 0;font-family: Arial, Helvetica, sans-serif;font-weight: normal;text-transform: uppercase;font-size: 11px;line-height: 16px;-webkit-text-size-adjust:none;"><a href="'.$baseurl.'/lighting.html" style="color: #888;text-decoration: none;">Lighting</a></td>
												<td align="center" style="border-collapse: collapse;margin: 0;padding: 0;font-family: Arial, Helvetica, sans-serif;font-weight: normal;text-transform: uppercase;font-size: 11px;line-height: 16px;-webkit-text-size-adjust:none;"><a href="'.$baseurl.'/leisure.html" style="color: #888;text-decoration: none;">Leisure</a></td>
												<td align="center" style="border-collapse: collapse;margin: 0;padding: 0;font-family: Arial, Helvetica, sans-serif;font-weight: normal;text-transform: uppercase;font-size: 11px;line-height: 16px;-webkit-text-size-adjust:none;"><a href="'.$baseurl.'/designers/" style="color: #888;text-decoration: none;">Designers</a></td>
												<td align="center" style="border-collapse: collapse;margin: 0;padding: 0;font-family: Arial, Helvetica, sans-serif;font-weight: normal;text-transform: uppercase;font-size: 11px;line-height: 16px;-webkit-text-size-adjust:none;"><a href="http://volo-design.tumblr.com" style="color: #888;text-decoration: none;">The Journal</a></td>
												<td align="center" style="border-collapse: collapse;margin: 0;padding: 0;font-family: Arial, Helvetica, sans-serif;font-weight: normal;text-transform: uppercase;font-size: 11px;line-height: 16px;-webkit-text-size-adjust:none;"><a href="'.$baseurl.'/about/" style="color: #888;text-decoration: none;">About Us</a></td>
											</tr>
										</table>

									</td>
								</tr>
							</table>
							<!-- // End Template Links \ -->
						</td>
					</tr>
					
					
					</table></td></tr>
					
					
														</table></td>
													</tr>
											</table></td>
											<td valign="top" width="20" height="470" style="background: #cccfd1;border-collapse: collapse;line-height:0;font-size:0;"><img src="'.$baseurl.'/skin/frontend/default/respond/invitefriend/table-right.png" width="20" height="470" alt="" style="vertical-align: middle;display: block;border: 0;outline: none;text-decoration: none;height: auto;line-height: 100%;"></td>
										</tr>
								</table></td>
							</tr>
							<tr>
								<td height="20" style="border-collapse: collapse;line-height:0;font-size:0;"><img src="'.$baseurl.'/skin/frontend/default/respond/invitefriend/table-bottom.png" width="670" height="20" alt="" style="vertical-align: top;display: block;outline: none;text-decoration: none;border: 0;height: auto;line-height: 100%;"></td>
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
					"from_name" =>  "VOLO Design",
					"subject" => "Welcome! Here's a coupon for your next order at VOLODesign.com",
					"to" => array(array("email" => $email)),
					"track_opens" => true,
					"track_clicks" => true,
					"auto_text" => true,
					"tags"=>array("newsletter signup")
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

/*******************
End of Newletter Signup
******************/

			Mage::getModel('core/cookie')->set('Subscribed', true);
                    $session->addSuccess($this->__('Thank you for your subscription.'));
                }
            }
            catch (Mage_Core_Exception $e) {
                $session->addException($e, $this->__('There was a problem with the subscription: %s', $e->getMessage()));
            }
            catch (Exception $e) {
                $session->addException($e, $this->__('There was a problem with the subscription.'));
            }
        }
        $this->_redirectReferer();
    }

    /**
     * Subscription confirm action
     */
    public function confirmAction()
    {
        $id    = (int) $this->getRequest()->getParam('id');
        $code  = (string) $this->getRequest()->getParam('code');

        if ($id && $code) {
            $subscriber = Mage::getModel('newsletter/subscriber')->load($id);
            $session = Mage::getSingleton('core/session');

            if($subscriber->getId() && $subscriber->getCode()) {
                if($subscriber->confirm($code)) {
                    $session->addSuccess($this->__('Your subscription has been confirmed.'));
                } else {
                    $session->addError($this->__('Invalid subscription confirmation code.'));
                }
            } else {
                $session->addError($this->__('Invalid subscription ID.'));
            }
        }

        $this->_redirectUrl(Mage::getBaseUrl());
    }

    /**
     * Unsubscribe newsletter
     */
    public function unsubscribeAction()
    {
        $id    = (int) $this->getRequest()->getParam('id');
        $code  = (string) $this->getRequest()->getParam('code');

        if ($id && $code) {
            $session = Mage::getSingleton('core/session');
            try {
                Mage::getModel('newsletter/subscriber')->load($id)
                    ->setCheckCode($code)
                    ->unsubscribe();
                $session->addSuccess($this->__('You have been unsubscribed.'));
            }
            catch (Mage_Core_Exception $e) {
                $session->addException($e, $e->getMessage());
            }
            catch (Exception $e) {
                $session->addException($e, $this->__('There was a problem with the un-subscription.'));
            }
        }
        $this->_redirectReferer();
    }

	 public function checkemailAction()
        {
                $Subscribed=Mage::getModel('core/cookie')->get('Subscribed');
                if ($Subscribed)
                {
                        $result=true;
                }
                else
                {
                        $result=false;
                        $Id= Mage::getSingleton('customer/session')->getCustomer()->getId();
                        $write_old = Mage::getSingleton('core/resource')->getConnection('core_write');
                        $select="SELECT * FROM `newsletter_subscriber` where customer_id='".$Id."'";
                        $row = $write_old->fetchRow($select);
                        if (($Id!=0) && ($row['subscriber_status']==1))
                        {
                                $result=true;
                        }
                        else
                        {
                                $result=false;
                        }
                }
                print_r($result);
//              print_r(Mage::helper('core')->jsonEncode($row));
        }


}
