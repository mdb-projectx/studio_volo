<?php
#http://store.example.com/curebit/add/?coupon=xyz123
class Curebit_SocialReferrals_Model_Add
{
    const COUPON_PARAM = '_c';
    const NO_SUCH_CODE = 'No Such Coupon Code';
    const MESSAGE_DEFERED = 'Coupon will be applied after first item is placed in cart';
    
    protected function _getCodeFromRequest($request)
    {
        //validate post
        $coupon = $request->getParam(self::COUPON_PARAM);
        if(!$coupon)
        {
            $post = $request->getPost();
            $coupon = array_key_exists(self::COUPON_PARAM,$post) ? $post[self::COUPON_PARAM] : $coupon;
        }        
        if(!$coupon)
        {
            Mage::throwException(self::NO_SUCH_CODE);
        }
        return strip_tags($coupon);    
    }
    
    protected function _validateCouponCode($coupon)
    {
        //validate coupon code
        $codes = Mage::getModel('salesrule/coupon')->getCollection()
        ->addFieldToFilter('code',$coupon);
        
        $o = $codes->getFirstItem();
        
        if($o->getCode() != $coupon)
        {
            Mage::throwException(self::NO_SUCH_CODE);
        }
        
        return true;
    }

    /**
    * We can't apply a coupon to an empty cart, so if there's
    * no items, defer the setting
    */    
    protected function _applyCouponCode($coupon)
    {
        if(!$this->_cartHasItems())
        {
            //defering code here
            Mage::getModel('curebit_socialreferrals/session')->addData(array(
            self::COUPON_PARAM=>$coupon));
            return self::MESSAGE_DEFERED;
        }
        
        self::applyCouponCode($coupon);
    }
    
    //blindly assumes it's safe to apply the code
    //"cheats" by using the controller action, ensureing
    //we pickup any weird version specific magento information
    //
    //will redirect as though posted to cart: @todo -- fix this
    static public function applyCouponCode($coupon)
    {
        require_once 'Mage/Checkout/controllers/CartController.php';		    
        $request    = Mage::app()->getRequest();
        $request->setParam('coupon_code',$coupon);
        $response   = Mage::app()->getResponse();            
        $controller = new Mage_Checkout_CartController(
            $request,
            $response
        );		    
        $controller->couponPostAction();    
    }
    
    protected function _cartHasItems()
    {
        return Mage::getSingleton('checkout/cart')->getQuote()->getItemsCount();
    }
    
    public function addCoupon($request)
    {
        $coupon = $this->_getCodeFromRequest($request);
        $this->_validateCouponCode($coupon);

        $message = $this->_applyCouponCode($coupon);
        
        return $message;
    }
    
    static protected $_addCouponAfterControllerAction=false;
    public function cartItemAdded($observer)
    {
        $coupon = Mage::getModel('curebit_socialreferrals/session')->getData(self::COUPON_PARAM);
        if(!$coupon)
        {
            return;
        }

        //mark for a redirect.  quote object still won't have any items, 
        //so we can't add the coupon when the item's added to the cart
        //a second post controller action event will handle this
        self::$_addCouponAfterControllerAction = $coupon;
    }
    
    public function addCouponAfterCartAdd($observer)
    {
        if(!self::$_addCouponAfterControllerAction)
        {
            return;
        }
        $coupon = Mage::getModel('curebit_socialreferrals/session')->getData(self::COUPON_PARAM);
        if(!$coupon)
        {
            return;
        }
                
        self::applyCouponCode($coupon);                
        Mage::getModel('curebit_socialreferrals/session')->setData(array(
        self::COUPON_PARAM=>false));        
    }
        
    public function addCouponCodeFromUrl($observer)
    {
        $request            = Mage::app()->getRequest();
        $response           = Mage::app()->getResponse();
        $headers_original   = $response->getHeaders();

        $param = $request->getParam(self::COUPON_PARAM);
        if(!$param)
        {
            return;
        }
        
        try
        {
            $message = Mage::getModel('curebit_socialreferrals/add')
                ->addCoupon($request);            
        }
        catch(Exception $e)
        {
            $message = $e->getMessage();
        }
        
        //reapply headers to un-do redirect added to by coupon adding code
        $response->clearHeaders();
        foreach($headers_original as $item)
        {
            $response->setHeader($item['name'],$item['value'],$item['replace']);
        }

        Mage::Log($message,Zend_Log::INFO);
    }
    
}
