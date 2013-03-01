<?php
require_once 'Mage/Checkout/controllers/CartController.php';
require_once 'app/Mage.php';
Mage::app();
class Excellence_Ajax_IndexController extends Mage_Checkout_CartController
{
	public function addAction()
	{
		$cart   = $this->_getCart();
		$params = $this->getRequest()->getParams();
		if($params['isAjax'] == 1){
			$response = array();
			try {
				if (isset($params['qty'])) {
					$filter = new Zend_Filter_LocalizedToNormalized(
					array('locale' => Mage::app()->getLocale()->getLocaleCode())
					);
					$params['qty'] = $filter->filter($params['qty']);
				}

				$product = $this->_initProduct();
				$related = $this->getRequest()->getParam('related_product');

$_product=Mage::getModel('catalog/product')->load($params['product']);
$relatedProductsId=$_product->getRelatedProductIds();
    $relatedProducts=array();
    foreach($relatedProductsId as $relatedProductId)
    {   
	$_relatedProduct = Mage::getModel('catalog/product')->load($relatedProductId);
	$image = Mage::getModel('catalog/product_media_config')->getMediaUrl($_relatedProduct->getThumbnail());
	//$image = Mage::getModel('catalog/product')->load($relatedProductId)->getImageUrl();
	$name = Mage::getModel('catalog/product')->load($relatedProductId)->getName();
	$price = Mage::getModel('catalog/product')->load($relatedProductId)->getPrice();
	$price = Mage::helper('core')->formatPrice($price, false);
	$retailprice = Mage::getModel('catalog/product')->load($relatedProductId)->getData('price_typical');
	$retailprice = Mage::helper('core')->formatPrice($retailprice, false);
	$link = Mage::getModel('catalog/product')->load($relatedProductId)->getProductUrl();
	$addlink = Mage::getUrl('checkout/cart/add', array('product'=>$relatedProductId));
        $relatedProducts[] = array('image'=>$image,'name'=>$name,'price'=>$price,'retailprice'=>$retailprice,'link'=>$link,'id'=>$relatedProductId,'addlink'=>$addlink);
    }
//var_dump($relatedProducts);
$response['related'] = $relatedProducts;

				/**
				 * Check product availability
				 */
				if (!$product) {
					$response['status'] = 'ERROR';
					$response['message'] = $this->__('Unable to find Product ID');
				}

				$cart->addProduct($product, $params);
				if (!empty($related)) {
					$cart->addProductsByIds(explode(',', $related));
				}

				$cart->save();

				$this->_getSession()->setCartWasUpdated(true);

				/**
				 * @todo remove wishlist observer processAddToCart
				 */
				Mage::dispatchEvent('checkout_cart_add_product_complete',
				array('product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse())
				);

				if (!$cart->getQuote()->getHasError()){
					$message = $this->__('%s was added to your shopping cart.', Mage::helper('core')->htmlEscape($product->getName()));
					$response['status'] = 'SUCCESS';
					$response['message'] = $message;
					//New Code Here
					$this->loadLayout();
					$toplink = $this->getLayout()->getBlock('cart_sidebar')->toHtml();
/*
					$sidebar_block = $this->getLayout()->getBlock('cart_sidebar');
					Mage::register('referrer_url', $this->_getRefererUrl());
					$sidebar = $sidebar_block->toHtml();
*/
					$response['toplink'] = $toplink;
					$response['sidebar'] = $sidebar;
					$response['productName'] = $product->getName();
					$response['productPrice'] = Mage::helper('core')->formatPrice($product->getPrice(), false);
					$response['productImage'] = str_replace('75x75','308x190',$_product->getThumbnailUrl());
					$itemCount = 0;
					//$itemCount = Mage::helper('checkout/cart')->getCart()->getItemsCount();
					$session = Mage::getSingleton('checkout/session');
					foreach ($session->getQuote()->getAllItems() as $item) {
						$itemCount += $item->getQty();
					}
					$response['itemcount'] = 'MY CART ('.$itemCount.' ITEMS)';
				}

			} catch (Mage_Core_Exception $e) {
				$msg = "";
				if ($this->_getSession()->getUseNotice(true)) {
					$msg = $e->getMessage();
				} else {
					$messages = array_unique(explode("\n", $e->getMessage()));
					foreach ($messages as $message) {
						$msg .= $message.'<br/>';
					}
				}

				$response['status'] = 'ERROR';
				$response['message'] = $msg;
			} catch (Exception $e) {
				$response['status'] = 'ERROR';
				$response['message'] = $this->__('Cannot add the item to shopping cart.');
				Mage::logException($e);
			}
			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
			return;
		}else{
			return parent::addAction();
		}
	}
	

	public function deleteAction(){
        $id = (int) $this->getRequest()->getParam('id');
        if ($id) {
            try {
                $this->_getCart()->removeItem($id)
                  ->save();
                $this->_getSession()->setCartWasUpdated(true);
                if (!$this->_getCart()->getQuote()->getHasError()){
                    
                    $response['status'] = 'SUCCESS';
                    $response['message'] = $message;
                    //New Code Here
                    $this->loadLayout();
                    $toplink = $this->getLayout()->getBlock('top.links')->toHtml();
                    $sidebar_block = $this->getLayout()->getBlock('cart_sidebar');
                    Mage::register('referrer_url', $this->_getRefererUrl());
                    $sidebar = $sidebar_block->toHtml();
                                      
                    $response['toplink'] = $toplink;
                    $response['sidebar'] = $sidebar;
                                    
                }
                
                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
            return;
            } catch (Exception $e) {
                $this->_getSession()->addError($this->__('Cannot remove the item.'));
                Mage::logException($e);
            }
        }
        
        }

}
