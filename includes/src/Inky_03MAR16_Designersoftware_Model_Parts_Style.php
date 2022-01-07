<?php

class Inky_Designersoftware_Model_Parts_Style extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('designersoftware/parts_style');
    }
    
    public function getPartsStyleCollection(){
		$collection =  Mage::getModel('designersoftware/parts_style')->getCollection()
									->addFieldToFilter('status',1);
		
		
		return $collection;
	}
	
	public function getCollectionById($id){
		$collection = $this->getPartsStyleCollection()
									->addFieldToFilter('parts_style_id',$id)
									->getFirstItem();
		return $collection;				
	}
}
