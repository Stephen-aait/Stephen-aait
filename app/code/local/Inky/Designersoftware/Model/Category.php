<?php

class Inky_Designersoftware_Model_Category extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('designersoftware/category');
    }
    
    public function getCategoryCollection(){
		$collection =  Mage::getModel('designersoftware/category')->getCollection()
									->addFieldToFilter('status',1);
		
		
		return $collection;
	}
	
	public function getCategoryCollectionById($id){
		$collection = $this->getCategoryCollection();
		$collection = $collection->addFieldToFilter('category_id',$id)	
							->getFirstItem();
		return $collection;
	}
}
