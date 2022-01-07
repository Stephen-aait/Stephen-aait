<?php

class Inky_Designersoftware_Helper_Category extends Mage_Core_Helper_Abstract
{  
   public function getCategoryNameById($category_id){
	   if($category_id>0){
			$collection = Mage::getModel('designersoftware/category')->getCollection()
						->addFieldToFilter('category_id', $category_id)
						->getFirstItem();
			
			if(count($collection->getData())>0){			
				return $collection->getTitle();
			} else {
				return false;
			}
		}
   } 
   
   public function getAllCategorys(){
	   $clipartCategoryArr=array();
		$clipartCategoryArr['']='';
		$collection = Mage::getModel('designersoftware/category')->getCollection()								
								->setOrder('title',asc);
								
		if($collection->getSize()>0){
			foreach($collection as $collections){				
				$clipartCategoryArr[$collections->getId()]= $collections->getTitle();	
			}
		}
		
		return $clipartCategoryArr;
   }

}
