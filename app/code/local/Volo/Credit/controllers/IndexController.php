<?php

class Volo_Credit_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
	$Id= Mage::getSingleton('customer/session')->getCustomer()->getId();
        $write_old = Mage::getSingleton('core/resource')->getConnection('core_write');
        $select="CALL salesrule_coupon_getcoupon('".$Id."')";
        if ($row = $write_old->fetchRow($select))
        {
                $result="Invite code:".$row['invite']." - Private code:".$row['private'];
                $response = array('invite' => $row['invite'], 'private' => $row['private'],'discount_amount'=>$row['discount_amount']);
        }
        else
        {
                        $result='User not found';
        }

//      print_r($result);

        $response = Mage::helper('core')->jsonEncode($response);
//        $this->getResponse()->setBody($response);
var_dump($response.'<br>');
var_dump($result);
	$this->loadLayout(array('default'));
	$this->renderLayout();
    }
}
