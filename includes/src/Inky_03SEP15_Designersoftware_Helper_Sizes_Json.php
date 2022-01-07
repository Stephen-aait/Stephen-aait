<?php

class Inky_Designersoftware_Helper_Sizes_Json extends Mage_Core_Helper_Abstract
{
	public function load($data){		
					
		$sizesCollection  = Mage::getModel('designersoftware/sizes')->getSizesCollection();	
							
		foreach($sizesCollection as $sizes):		
			$array['id']				= $sizes->getId();
			$array['label']				= $sizes->getTitle();
			$array['type']				= 'sizes';
			$array['thumb']				= $sizes->getFilename();
			$array['colorable']			= $sizes->getColorable();
			$array['color']				= $sizes->getTitle();
			$array['price']				= $sizes->getPrice();
			
			$collection = $this->getColorCollectionById($sizes->getDefaultColorId());
			$array['defaultColorId']	= $collection->getId();
			$array['defaultColorTitle']	= $collection->getTitle();
			$array['defaultColorCode'] 	= $collection->getColorcode();
			
			$array['defaultColorPrice'] = Mage::getModel('designersoftware/sizes')->getColorPrice($sizes->getId(), $sizes->getDefaultColorId());						
			
			$sizesDetails[] = $array;
		endforeach;
			
			$finalJSON['sizes'] = $sizesDetails;
			
		return $finalJSON;
	}
	
	public function getColorCollectionById($colorId){
		$collection = Mage::getModel('designersoftware/color')->getColorCollectionById($colorId);
		return $collection;
	}
}
