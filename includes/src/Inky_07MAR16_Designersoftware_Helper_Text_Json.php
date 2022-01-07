<?php

class Inky_Designersoftware_Helper_Text_Json extends Mage_Core_Helper_Abstract
{
	public function load($data){			
					
		$textCollection  = Mage::getModel('designersoftware/text')->getCollection()->addFieldToFilter('status',1);	
							
		foreach($textCollection as $text):		
			$array['id']			= $text->getId();
			$array['title']			= $text->getTitle();
			$array['cost']			= $text->getCost();			
			$array['minPrice']		= $text->getMinPrice();	
			$array['maxCharacter']	= $text->getMaxChar();
			$array['filename']		= $text->getFilename();		
			
			$textDetails[] = $array;
		endforeach;
		
			$finalJSON['printings'] 	= $textDetails;
			
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
