<?php
 
class Inchoo_SimpleContact_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
		/*
        //Get current layout state
        $this->loadLayout();   
 
        $block = $this->getLayout()->createBlock(
            'Mage_Core_Block_Template',
            'inchoo.simple_contact',
            array(
                'template' => 'inchoo/simple_contact.phtml'
            )
        );
 
        $this->getLayout()->getBlock('content')->append($block);
        //$this->getLayout()->getBlock('right')->insert($block, 'catalog.compare.sidebar', true);
 
        $this->_initLayoutMessages('core/session');
 
        $this->renderLayout();
		*/
    }
 
    public function sendemailAction()
    {
        $post = $this->getRequest()->getPost();
        if ( $post ) {
            try {
                $postObject = new Varien_Object();
                $postObject->setData($post);

                $error = false;

				// CHECK FOR ERRORS
                if (!Zend_Validate::is(trim($post['name']) , 'NotEmpty')) {
                    $error = true;
                }
                if (!Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
                    $error = true;
                }
				/*
                if (!Zend_Validate::is(trim($post['phone']) , 'NotEmpty')) {
                    $error = true;
                }
				*/
                if (!Zend_Validate::is(trim($post['address']) , 'NotEmpty')) {
                    $error = true;
                }
                if (!Zend_Validate::is(trim($post['city']) , 'NotEmpty')) {
                    $error = true;
                }
                if (!Zend_Validate::is(trim($post['state']) , 'NotEmpty')) {
                    $error = true;
                }
                if (!Zend_Validate::is(trim($post['zip']) , 'NotEmpty')) {
                    $error = true;
                }
                if (Zend_Validate::is(trim($post['hideit']), 'NotEmpty')) {
                    $error = true;
                }
                if ($error) {
                    throw new Exception();
                }
				
				// PROCESS ORDER
				
				// TO DO: SANITIZE INPUTS
				
				$customerName = filter_var($post['name'], FILTER_SANITIZE_STRING);
				$customerEmail = filter_var($post['email'], FILTER_SANITIZE_EMAIL);
				// $customerPhone = $post['phone'];
				$customerAddress = filter_var($post['address'], FILTER_SANITIZE_STRING);
				$customerAddress2 = filter_var($post['address2'], FILTER_SANITIZE_STRING);
				$customerCity = filter_var($post['city'], FILTER_SANITIZE_STRING);
				$customerState = filter_var($post['state'], FILTER_SANITIZE_STRING);
				$customerZip = filter_var($post['zip'], FILTER_SANITIZE_STRING);
				$customerID = '';
				$customerIP = $_SERVER['REMOTE_ADDR'];
				$orderID = '';
				$customerFax = trim(filter_var($post['fax']), FILTER_SANITIZE_STRING);
				
                if(empty($customerFax)) {
                    
                    if(Mage::getSingleton('customer/session')->isLoggedIn()) {
                        $customerData = Mage::getSingleton('customer/session')->getCustomer();
                        $customerID = $customerData->getId();
                    }

                    // Connect
                    $dbhost='volo.ceaw3hvwerv9.us-east-1.rds.amazonaws.com';
                    $dbuser='mdb_admin';
                    $dbpasswd='million888';
                    $dbname='volo';

                    $link = mysql_connect($dbhost, $dbuser, $dbpasswd);
                    if (!$link) {
                        die('Could not connect: ' . mysql_error());
                    }

                    $db_selected = mysql_select_db($dbname, $link);
                    if (!$db_selected) {
                            die ('Can\'t use volo_staging : ' . mysql_error());
                    } 

                    $query = "INSERT INTO `samples_order` (`customer_id`,`street`,`street2`,`region`,`postcode`,`city`,`customer_email`,`customer_name`,`remote_ip`) VALUES('".$customerID."', '".$customerAddress."','".$customerAddress2."','".$customerState."','".$customerZip."','".$customerCity."','".$customerEmail."','".$customerName."','".$customerIP."')";

                    $result = mysql_query($query);
                    if (!$result) {
                        die('Invalid query: ' . mysql_error());
                    }

                    $query = "SELECT * FROM `samples_order` WHERE customer_email = '".$customerEmail."' ORDER BY entity_id DESC LIMIT 1";

                    $result = mysql_query($query);
                    if (!$result) {
                        Mage::getSingleton('core/session')->addError('Your order could not be sent. Please email contact@volodesign.com for assistance.');
                    } else {

                        while($row = mysql_fetch_array($result)) {
                            $orderID = $row['entity_id'];
                        }
                        if(empty($orderID)) {
                            Mage::getSingleton('core/session')->addError('Your order could not be sent. Please email contact@volodesign.com for assistance.');
                        } else {

                            foreach($post['swatches'] as $swatch) {
                                $query = "INSERT INTO `samples_order_item` (`order_id`,`item_no`,`item_name`,`quantity`) VALUES ('".$orderID."','".$swatch."','".$swatch."','1')";

                                $result = mysql_query($query);
                                if (!$result) {
                                    Mage::getSingleton('core/session')->addError('Your order could not be sent. Please email contact@volodesign.com for assistance.');
                                }
                            }

                            /*
                            $mail = Mage::getModel('core/email')
                                    ->setBodyText('test')
                                    ->setToName($customerName)
                                    ->setToEmail($customerEmail)
                                    ->setFromEmail('contact@volodesign.com')
                                    ->SetFromName('Some Name')
                                    ->setSubject('Test Inchoo_SimpleContact Module for Magento')
                                    ->setType('html');
                            try {
                                $mail->send();
                            }        
                            catch(Exception $ex) {
                                Mage::getSingleton('core/session')->addError('Unable to send email. Sample of a custom notification error from Inchoo_SimpleContact.');
                            }
                            */

                            $service_url = 'http://mandrillapp.com/api/1.0/messages/send.json';
                            $curl = curl_init($service_url);
                            $curl_post_data = array(
                                    "key" => "8de2f48d-6932-4918-9af1-ebd722957d68",
                                    'message' => array(
                                            "html" => 'Hi!<br><br>We received your fabric sample order! Our team is preparing your fabric samples for shipment. Please allow 3-5 business days for the fabric samples to arrive. Thanks for visiting VOLO Design!<br><br>Cheers,<br><a href="http://www.volodesign.com">VOLO Design</a>',
                                            "text" => 'Hi!

    We received your fabric sample order! Our team is preparing your fabric samples for shipment. Please allow 3-5 business days for the fabric samples to arrive. Thanks for visiting VOLO Design!

    Cheers,
    VOLO Design

    http://www.volodesign.com',
                                            "from_email" => "contact@volodesign.com",
                                            "from_name" =>  "VOLO Design",
                                            "subject" => "VOLO Fabric Sample Order",
                                            "to" => array(array("email" => $customerEmail)),
                                            "track_opens" => true,
                                            "track_clicks" => true,
                                            "auto_text" => true,
                                            "tags"=>array("fabric samples")
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
                                Mage::getSingleton('core/session')->addError('Your order could not be sent. Please email contact@volodesign.com for assistance.');
                            }
                        }

                        Mage::getSingleton('core/session')->addSuccess('Thank you, your order has been sent. Please allow 3-5 business days for the fabric samples to arrive.');
                    }

                    mysql_close($link);

				    $this->_redirect('fabric-samples.html'); 
                } else {
                    // fax filled out, treat as spam
                    Mage::getSingleton('core/session')->addSuccess('Thank you, your order has been sent. Please allow 3-5 business days for the fabric samples to arrive.');
				    $this->_redirect('fabric-samples.html'); 
                }
                
                return;
            } catch (Exception $e) {
                
				Mage::getSingleton('core/session')->addError('Your order could not be sent. Please email contact@volodesign.com for assistance.');
				
				$this->_redirect('fabric-samples.html'); 
				
                return;
            }

        } else {
			$this->_redirect('fabric-samples.html'); 
        }
		
		
		/********/
		
		
//        $this->_redirect('fabric-samples.html'); 
 
 /*
        $mail = new Zend_Mail();
        $mail->setBodyText($params['comment']);
        $mail->setFrom($params['email'], $params['name']);
        $mail->addTo('somebody_else@example.com', 'Some Recipient');
        $mail->setSubject('Test Inchoo_SimpleContact Module for Magento');
        try {
            $mail->send();
        }        
        catch(Exception $ex) {
            Mage::getSingleton('core/session')->addError('Unable to send email. Sample of a custom notification error from Inchoo_SimpleContact.');
 
        }
 
        //Redirect back to index action of (this) inchoo-simplecontact controller
        $this->_redirect('inchoo-simplecontact/'); */
    }
}
 
?>