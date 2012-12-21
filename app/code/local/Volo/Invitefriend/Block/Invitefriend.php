<?php

class Volo_Invitefriend_Block_Invitefriend extends Mage_Core_Block_Template
{
	public function getInviteCode()
	{
		$Id= Mage::getSingleton('customer/session')->getCustomer()->getId();
		$write_old = Mage::getSingleton('core/resource')->getConnection('core_write');
		$result='';
		$select="SELECT coupon_code FROM salesrule_customer_mapping where customer_id='".$Id."' and coupon_type='INVITE'";		
		if ($row = $write_old->fetchRow($select))
		{
			$result=$row['coupon_code'];                
		}        
		return $result;
	}

	public function getBaseURL()
	{
		return Mage::getBaseUrl();
	}
}
?>
