<?php

class Inky_Designersoftware_Model_Text extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('designersoftware/text');
    }
    
    public function getTextCollection(){
		$collection =  Mage::getModel('designersoftware/text')->getCollection()
									->addFieldToFilter('status',1);
		
		
		return $collection;
	}
	
	public function getTextCollectionById($id){
		$collection = $this->getTextCollection();
		$collection = $collection->addFieldToFilter('text_id',$id)	
							->getFirstItem();
		return $collection;
	}
}
