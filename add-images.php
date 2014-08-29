<?php
require_once 'app/Mage.php';
Mage::app();
Mage::app()->getStore()->setId(Mage_Core_Model_App::ADMIN_STORE_ID);
$importDir = Mage::getBaseDir('media') . DS . 'incoming/';
$media = Mage::getModel('catalog/product_attribute_media_api');

$file_handle = fopen("images.csv", "r");
while (!feof($file_handle) ) {
	$line_of_text = fgetcsv($file_handle, 1024);
	$productSKU = $line_of_text[0];
	$ourProduct = Mage::getModel('catalog/product')->loadByAttribute('sku',$productSKU);
	if($ourProduct) {
		$fileName = $line_of_text[1];
		$filePath = $importDir.$fileName;
		if (file_exists($filePath)) {
			$pathInfo = pathinfo($filePath);

			switch($pathInfo['extension']){
				case 'png':
					$mimeType = 'image/png';
					break;
				case 'jpg':
					$mimeType = 'image/jpeg';
					break;
				case 'gif':
					$mimeType = 'image/gif';
					break;
			}
			
			if( strstr($fileName, 'content-w') || strstr($fileName, 'content_w') ) {
				// $ourProduct->addImageToMediaGallery($filePath, array('image'), false, false);
				$position = 1;
				$type = 'image';
				$exclude = 0;
			} elseif( strstr($fileName, 'content-1') || strstr($fileName, 'content_1') ) {
				// $ourProduct->addImageToMediaGallery($filePath, array('feature_image'), false, false);
				$position = 2;
				$type = 'feature_image';
				$exclude = 0;
			} elseif( strstr($fileName, 'content-2') || strstr($fileName, 'content_2') ) {
				// $ourProduct->addImageToMediaGallery($filePath, array('feature_image2'), false, false);
				$position = 3;
				$type = 'feature_image2';
				$exclude = 0;
			} elseif( strstr($fileName, 'content-3') || strstr($fileName, 'content_3') ) {
				// $ourProduct->addImageToMediaGallery($filePath, array('feature_image3'), false, false);
				$position = 4;
				$type = 'feature_image3';
				$exclude = 0;
			} elseif( strstr($fileName, 'content-4') || strstr($fileName, 'content_4') ) {
				// $ourProduct->addImageToMediaGallery($filePath, array('feature_image4'), false, false);
				$position = 5;
				$type = 'feature_image4';
				$exclude = 0;
			} elseif( strstr($fileName, 'content-5') || strstr($fileName, 'content_5') ) {
				// $ourProduct->addImageToMediaGallery($filePath, array('feature_image4'), false, false);
				$position = 6;
				$type = '';
				$exclude = 0;
			} elseif( strstr($fileName, 'content-6') || strstr($fileName, 'content_6') ) {
				// $ourProduct->addImageToMediaGallery($filePath, array('feature_image4'), false, false);
				$position = 7;
				$type = '';
				$exclude = 0;
			} elseif( strstr($fileName, 'content-7') || strstr($fileName, 'content_7') ) {
				// $ourProduct->addImageToMediaGallery($filePath, array('feature_image4'), false, false);
				$position = 8;
				$type = '';
				$exclude = 0;
			} elseif( strstr($fileName, 'content-8') || strstr($fileName, 'content_8') ) {
				// $ourProduct->addImageToMediaGallery($filePath, array('feature_image4'), false, false);
				$position = 9;
				$type = '';
				$exclude = 0;
			} elseif( strstr($fileName, 'content-9') || strstr($fileName, 'content_9') ) {
				// $ourProduct->addImageToMediaGallery($filePath, array('feature_image4'), false, false);
				$position = 10;
				$type = '';
				$exclude = 0;
			} elseif( strstr($fileName, 'content-10') || strstr($fileName, 'content_10') ) {
				// $ourProduct->addImageToMediaGallery($filePath, array('feature_image4'), false, false);
				$position = 11;
				$type = '';
				$exclude = 0;
			} elseif( strstr($fileName, 'thumbnail-1') || strstr($fileName, 'thumbnail_1') ) {
				// $ourProduct->addImageToMediaGallery($filePath, array('thumbnail'), false, true);
				$position = 12;
				$type = 'thumbnail';
				$exclude = 1;
			} elseif( strstr($fileName, 'thumbnail-2') || strstr($fileName, 'thumbnail_2') ) {
				// $ourProduct->addImageToMediaGallery($filePath, array('small_image'), false, true);
				$position = 12;
				$type = 'small_image';
				$exclude = 1;
			} elseif( strstr($fileName, 'thumbnail-3') || strstr($fileName, 'thumbnail_3') ) {
				// $ourProduct->addImageToMediaGallery($filePath, array('thumbnail'), false, true);
				$position = 12;
				$type = '';
				$exclude = 1;
			} elseif( strstr($fileName, 'thumbnail-4') || strstr($fileName, 'thumbnail_4') ) {
				// $ourProduct->addImageToMediaGallery($filePath, array('small_image'), false, true);
				$position = 12;
				$type = '';
				$exclude = 1;
			} elseif( strstr($fileName, 'thumbnail-5') || strstr($fileName, 'thumbnail_5') ) {
				// $ourProduct->addImageToMediaGallery($filePath, array('small_image'), false, true);
				$position = 12;
				$type = '';
				$exclude = 1;
			} else {
				// $ourProduct->addImageToMediaGallery($filePath, array(), false, false);
				$position = 12;
				$type = '';
				$exclude = 0;
			}		
			// $ourProduct->save();
			
			$imageAttribute = $ourProduct->getResource()->getAttribute($type)->getFrontend()->getValue($ourProduct);
			
			if($imageAttribute == 'no_selection' || $imageAttribute = '' || !$imageAttribute) {			
				$newImage = array(
					'file' => array(
						'content' => base64_encode($filePath),
						'mime' => $mimeType,
						'name' => basename($filePath),
						),
					'label' => '', 
					'position' => $position,
					'types' => array($type),
					'exclude' => $exclude
				);
				$media->create($productSKU, $newImage);
				
				echo $productSKU . " " . $fileName . " done";
				echo "<br>";
			} else {
				echo $productSKU . " " . $fileName . " not done - image already uploaded";
				echo "<br>";
			}
			
			// Avatar
			$attribute = $ourProduct->getResource()->getAttribute('quote_author')->getFrontend()->getValue($ourProduct);
			$attribute = strtolower($attribute);			
			$imageAttribute = $ourProduct->getResource()->getAttribute('quote_avatar')->getFrontend()->getValue($ourProduct);
			
			if($imageAttribute == 'no_selection' || $imageAttribute = '' || !$imageAttribute) {
				$avatarFilePath = $importDir . 'avatars/' . $attribute . '.png';
				if (file_exists($avatarFilePath)) {
					$ourProduct->addImageToMediaGallery($avatarFilePath, array('quote_avatar'), false, true);			
					$ourProduct->save();
					
					echo 'avatar ' . $attribute . ' added<br>';
				} else {
					echo 'avatar not added<br>';
				}
			}
			
			echo '<br>';
		} else {
			echo $productSKU . " " . $fileName . " not done - file does not exist";
			echo "<br><br>";
		}	
	} else {
		echo $productSKU . " not done - product does not exist";
		echo "<br><br>";
	}
}
fclose($file_handle);
?>