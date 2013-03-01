<?php

class Volo_Credit_Block_Credit extends Mage_Core_Block_Template
{
        public function getCredit()
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
        	return $response;
	}

	public function getHistory()
	{
		$Id= Mage::getSingleton('customer/session')->getCustomer()->getId();
	        $read_old = Mage::getSingleton('core/resource')->getConnection('core_read');
		$select="SELECT c.customer_firstname, c.customer_lastname, b.coupon_type , DATE_FORMAT(c.created_at,'%m/%d/%Y') created_at, a.amount FROM salesrule_customer_log  a LEFT JOIN salesrule_customer_mapping b ON a.code=b.coupon_code LEFT JOIN sales_flat_order c ON a.order_id=c.entity_id WHERE a.type='DEPOSIT' AND a.customer_id='".$Id."' ORDER BY created_at DESC";
		$response=array();

		foreach ($read_old->fetchAll($select) as $arr_row) {
			$response[]=$arr_row;
		}
		return $response;
	}


	public function getCreditTotal()
	{
		$Id= Mage::getSingleton('customer/session')->getCustomer()->getId();
	        $read_old = Mage::getSingleton('core/resource')->getConnection('core_read');
	        $select="SELECT sum(a.amount) total FROM salesrule_customer_log  a LEFT JOIN salesrule_customer_mapping b ON a.code=b.coupon_code LEFT JOIN sales_flat_order c ON a.order_id=c.entity_id WHERE a.type='DEPOSIT' AND a.customer_id='".$Id."'";
        	if ($row = $read_old->fetchRow($select))
	        {
                	$response = $row['total']+0;
	        }
        	else
	        {
        		$response = 0;           
	        }
        	return $response;

	}
}
?>
