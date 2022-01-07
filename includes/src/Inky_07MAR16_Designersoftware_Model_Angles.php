<?php

class Inky_Designersoftware_Model_Angles extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('designersoftware/angles');
    }
    
    public function getAnglesCollection(){
		$collection =  Mage::getModel('designersoftware/angles')->getCollection()
									->addFieldToFilter('status',1);
		
		
		return $collection;
	}
	
	public function getAnglesCollectionById($id){
		$collection = $this->getAnglesCollection();
		$collection = $collection->addFieldToFilter('angles_id',$id)	
							->getFirstItem();
		return $collection;
	}
}
