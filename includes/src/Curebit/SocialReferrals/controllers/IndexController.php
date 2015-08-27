<?php

class Curebit_SocialReferrals_IndexController extends Mage_Core_Controller_Front_Action {        

    public function indexAction() {
        try {
            $curebitBlock = new Curebit_SocialReferrals_Block_Main();
            if (!$curebitBlock->isDebug()) {
              echo "Curebit Debug mode is not enabled.  Go to Configuration -> Checkout -> Curebit and set 'Debug Mode' to 'Yes'";
              return;
            }
            
            $orderNumber = Mage::app()->getRequest()->getParam('order_number');
            if (empty($orderNumber)) {
                $orderNumber = Mage::getModel('sales/order')->getCollection()->setOrder('increment_id','DESC')->setPageSize(1)->setCurPage(1)->getFirstItem()->getIncrementId();
                Mage::log('Curebit_SocialReferrals_IndexController, using default order ' . $orderNumber, null, 'curebit-socialreferrals.log');
            } else {
                Mage::log('Curebit_SocialReferrals_IndexController, using order ' . $orderNumber, null, 'curebit-socialreferrals.log');        
            }
            $jsCode = htmlentities($curebitBlock->getJSCodeByOrderId($orderNumber, true));
       			$output = <<<EOS
<html>
  <head>
    <title>Curebit Debugging</title>
  </head>
  <body>
      Curebit successfully installed!
      <br />
      JS code for order: %s
      <br />
      %s
  </body>
</html>
EOS;
            echo sprintf($output, $orderNumber, $jsCode);
        } catch (Exception $e) {
            echo 'Exception: ' . $e->getMessage();
        }
    }
}
