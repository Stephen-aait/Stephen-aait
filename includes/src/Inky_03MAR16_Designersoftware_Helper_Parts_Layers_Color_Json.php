<?php

class Inky_Designersoftware_Helper_Parts_Layers_Color_Json extends Mage_Core_Helper_Abstract
{
	public function load($data){			
		$partsLayersId = $data['partsLayersId'];			
		//$partsLayersId = 1;			
		$clipartColorCollection  = Mage::getModel('designersoftware/parts_layers')->getColorCollectionById($partsLayersId);			
									
		foreach($clipartColorCollection as $colorId):		
			$color = Mage::getModel('designersoftware/color')->getColorCollectionById($colorId);
			
			$array['id']		= $color->getId();
			$array['title']		= $color->getTitle();
			$array['colorCode']	= $color->getColorcode();	
			$array['price']		= Mage::getModel('designersoftware/parts_layers')->getColorPrice($partsLayersId, $color->getId()); 		
			
			$colorDetails[] = $array;
		endforeach;
			
			$finalJSON['colors'] = $colorDetails;
			
		return $finalJSON;
	}	
}
