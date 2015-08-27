<?php
class Curebit_SocialReferrals_Model_Curebit_Api extends Mage_Api_Model_Resource_Abstract
{
    public function create_coupon($args)
    {        
        $coupon = Mage::getModel('salesrule/rule');		    
        
        //limiting what can be set to ensure 
        //trackable support tickets
        $coupon->setData(array(
        //things the api call can change
        'name'                  => $args['name'],
        'description'           => $args['description'],
        'from_date'             => $args['from_date'],		    
        'to_date'               => $args['to_date'],
        'coupon_code'           => $args['coupon_code'],        
        'simple_action'         => $args['simple_action'],
        'discount_amount'       => $args['discount_amount'],
        'uses_per_coupon'       => $args['uses_per_coupon'],        
        
        //and there are hard coded for now
        'uses_per_customer'     => '0',             // zero means infinite        
        'is_active'             => '1',             // make them all active to start
        'stop_rules_processing' => '1',             // make this "king" of the coupons
        'is_advanced'           => '1',             // not sure, but all coupons have it
        'product_ids'           => null,            // applies to all products
        'sort_order'            => '0',             // UI thing
        'discount_qty'          => null,            // N/A
        'discount_step'         => '0',             // N/A
        'simple_free_shipping'  => '0',             // N/A
        'apply_to_shipping'     => '0',             // N/A        
        'is_rss'                => '0',             // Don't want this appearing the RSS feed
        'coupon_type'           => '2',             // This means it's a specific coupon with a code
        'customer_group_ids'    => Mage::getModel('customer/group')->getCollection()->getAllIds(),
        'website_ids'           => Mage::getModel('core/website')->getCollection()->addFieldToFilter('website_id',array('gt'=>0))->getAllIds(),        
        ));
        $coupon->save();
            
        $return = new stdClass();
        $return->success = true;
        $return->message = 'coupon created';
        $return->coupon = $coupon->getData();
        return $return;
    }
}
