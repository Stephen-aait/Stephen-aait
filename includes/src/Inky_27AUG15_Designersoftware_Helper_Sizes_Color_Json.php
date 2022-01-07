<?php

class Inky_Designersoftware_Helper_Sizes_Color_Json extends Mage_Core_Helper_Abstract
{
	public function load($data){			
		$sizesId = $data['sizesId'];			
		//$sizesId = 1;			
		$clipartColorCollection  = Mage::getModel('designersoftware/sizes')->getColorCollectionById($sizesId);			
									
		foreach($clipartColorCollection as $colorId):		
			$color = Mage::getModel('designersoftware/color')->getColorCollectionById($colorId);
			
			$array['id']		= $color->getId();
			$array['title']		= $color->getTitle();
			$array['colorCode']	= $color->getColorcode();	
			$array['price']		= Mage::getModel('designersoftware/sizes')->getColorPrice($sizesId, $color->getId()); 		
			
			$colorDetails[] = $array;
		endforeach;
			
			$finalJSON['colors'] = $colorDetails;
			
		return $finalJSON;
	}	
}
