<?php

class Inky_Designersoftware_Model_Parts extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('designersoftware/parts');
    }   
	
	public function getPartsCollection(){		
		$collection = Mage::getModel('designersoftware/parts')->getCollection()
							->addFieldToFilter('status',1);							
							
		return $collection;
	}
	
	public function getCollectionById($partsId=''){
		$collection = $this->getPartsCollection();
		if(!empty($partsId)):
			$collection = $collection->addFieldToFilter('parts_id',$partsId)			
							->getFirstItem();		
			return $collection;
		else:
			return false;		
		endif;			
	}
	
	public function getDataCollectionById($partsId=''){
		$collection = $this->getPartsCollection();
		
		if(!empty($partsId)):
		$collection = $collection->addFieldToFilter('parts_id',$partsId)												
						->getFirstItem()
						->getData();		
			return $collection;
		else:
			return false;		
		endif;		
	}
}
