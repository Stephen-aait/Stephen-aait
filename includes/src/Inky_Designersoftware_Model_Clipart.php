<?php

class Inky_Designersoftware_Model_Clipart extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('designersoftware/clipart');
    }
    
    public function getClipartCollection(){
		$collection =  Mage::getModel('designersoftware/clipart')->getCollection()
									->addFieldToFilter('status',1);		
		
		return $collection;
	}
	
	public function getCollectionById($clipartId){
		$collection =  $this->getClipartCollection()
							->addFieldToFilter('clipart_id',$clipartId)
							->getFirstItem();		
		
		return $collection;
	}
	
	public function getCollectionByCategoryId($categoryId){
		$collection =  $this->getClipartCollection()
							->addFieldToFilter('clipart_category_id', $categoryId);			
		
		
		return $collection;
	}
}
