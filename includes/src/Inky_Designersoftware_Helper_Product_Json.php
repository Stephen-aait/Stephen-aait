<?php

class Inky_Designersoftware_Helper_Product_Json extends Mage_Core_Helper_Abstract
{
	public function load($data){		
		$designId 	= $data['designId'];		
		
		$designersoftwareCollection = Mage::getModel('designersoftware/designersoftware')->getCollectionById($designId);				
		$productId = $designersoftwareCollection->getProductId();
		
		//$_productCollection = Mage::getModel('catalog/product')->load($productId);				
		
		$productDetails['designId'] 		= $designId;
		$productDetails['productId'] 		= $productId;
		$productDetails['title'] 			= $designersoftwareCollection->getTitle();
		$productDetails['styleDesignCode']	= $designersoftwareCollection->getStyleDesignCode();		
		
		$productDetails['sideDataArray']	= unserialize($designersoftwareCollection->getSideDataArray());
		$productDetails['designDataArray']	= unserialize($designersoftwareCollection->getDesignDataArray());			
		$productDetails['priceInfoArr'] 	= unserialize($designersoftwareCollection->getPriceInfoArr());
		$productDetails['partDropDownArr'] 	= unserialize($designersoftwareCollection->getPartDropdownArr());
		
		$productDetails['totalPrice'] 		= $designersoftwareCollection->getTotalPrice();
				
		
		return $productDetails;
	}
	
	/*public function getLeatherDetails($leatherArr){
		foreach($leatherArr as $leatherId):
			$leatherCollection = Mage::getModel('designersoftware/leather')->getCollectionById($leatherId);
			
			$leather['id'] 		= $leatherCollection->getId();
			$leather['title']	= $leatherCollection->getTitle();
			$leather['image'] 	= $leatherCollection->getFilename();
			
			$leatherDetails[] = $leather;
		endforeach;
		
		return $leatherDetails;
	}*/
}
