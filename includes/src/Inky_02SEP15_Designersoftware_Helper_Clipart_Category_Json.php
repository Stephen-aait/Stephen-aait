<?php

class Inky_Designersoftware_Helper_Clipart_Category_Json extends Mage_Core_Helper_Abstract
{
	public function load($data){			
					
		$clipartCategoryCollection  = Mage::getModel('designersoftware/clipart_category')->getCollection()
														->addFieldToFilter('status',1)
														->setOrder('sort_order','ASC');	
							
		foreach($clipartCategoryCollection as $category):		
			$array['id']		= $category->getId();
			$array['title']		= $category->getTitle();				
			
			$categoryDetails[] = $array;
		endforeach;
			
			$finalJSON['cats'] = $categoryDetails;
			
		return $finalJSON;
	}
}
