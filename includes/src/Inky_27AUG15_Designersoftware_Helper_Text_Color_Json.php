<?php

class Inky_Designersoftware_Helper_Text_Color_Json extends Mage_Core_Helper_Abstract
{
	public function load($data){			
					
		$textColorCollection  = Mage::getModel('designersoftware/color')->getCollectionByText();	
							
		foreach($textColorCollection as $color):		
			$array['id']		= $color->getId();
			$array['title']		= $color->getTitle();
			$array['colorCode']	= $color->getColorcode();			
			$array['price']		= $this->getPrice($color->getTextPrice());		
			
			$colorDetails[] = $array;
		endforeach;
			
			$finalJSON['colors'] = $colorDetails;
			
		return $finalJSON;
	}
	
	public function getPrice($price){
		if(!empty($price) && $price>0):
			return $price;
		else:			
			// system Config price for Clipart Color , it will be default color price for all colors
			$colorPrice = Mage::helper('designersoftware/system_config')->getTextColorPrice();
			if($colorPrice>0):
				return $colorPrice;
			else:			
				return 0;
			endif;
		endif;
	}
}
