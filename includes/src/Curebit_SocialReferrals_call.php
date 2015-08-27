<?php
    $api_username   = 'curebit';
    $api_secret     = 'password';
    $api_url        = 'http://magento1point6point1.dev/api/soap/?wsdl';
                            
    $client         = new SoapClient($api_url);
    $session_id     = $client->login($api_username,$api_secret);
    $result         = $client->call($session_id,
                      'curebit.create_coupon',
  array(array(
    //name of coupon in admin console
    'name'                  => 'Great Discount!',
    
    //description of coupon in admin console
    'description'           => 'Created via Curebit API',
    
    //first valid coupon date
    'from_date'             => '2012-03-19',		    
    
    //final date coupon is valid
    'to_date'               => '2012-03-30',
    
    //the code users will enter, max length of 32 (Magento imposed limit)
    'coupon_code'           => 'example_code_123',        
    
    //is this is percentage discount, or a fixed discount
    'simple_action'         => 'by_percent',    //or 'by_fixed'
    
    //amount of discount
    'discount_amount'       => '25',
    
    //how many times may this coupon be used
    'uses_per_coupon'       => '7',  
                      )));   
                      
                      
    echo 'Coupon Created' . "\n";                     