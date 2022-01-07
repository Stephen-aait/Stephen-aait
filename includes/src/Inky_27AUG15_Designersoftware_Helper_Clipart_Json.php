<?php

class Inky_Designersoftware_Helper_Clipart_Json extends Mage_Core_Helper_Abstract
{
	public function load($data){
		
		$clipartCategoryId = $data['clipartCategoryId'];		
					
		$clipartCollection  = Mage::getModel('designersoftware/clipart')->getCollectionByCategoryId($clipartCategoryId);	
							
		foreach($clipartCollection as $clipart):		
			$array['id']		= $clipart->getId();
			$array['label']		= $clipart->getTitle();
			$array['type']		= 'clipart';
			$array['thumb']		= $clipart->getFilename();
			$array['colorable']	= $clipart->getColorable();
			$array['color']		= $clipart->getTitle();
			$array['price']		= $clipart->getPrice();			
			
			$clipartDetails[] = $array;
		endforeach;
			
			$finalJSON['cliparts'] = $clipartDetails;
			
		return $finalJSON;
	}
}
