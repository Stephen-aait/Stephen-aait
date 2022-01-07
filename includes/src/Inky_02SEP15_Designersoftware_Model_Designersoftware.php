<?php

class Inky_Designersoftware_Model_Designersoftware extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('designersoftware/designersoftware');
    }
    
    public function getDesignersoftwareCollection(){
		$collection = Mage::getModel('designersoftware/designersoftware')->getCollection();
		
		return $collection;
	}
    
    public function getCollectionByCode($designCode){
		
		$collection = $this->getDesignersoftwareCollection()
								->addFieldToFilter('style_design_code', $designCode)
								->addFieldToFilter('status',1)
								->getFirstItem();
		
		return $collection;
	}
    
    public function getCollectionById($id){
		
		$collection = $this->getDesignersoftwareCollection()
								->addFieldToFilter('designersoftware_id', $id)
								->addFieldToFilter('status',1)
								->getFirstItem();
		
		return $collection;
	}
	
	public function getCollectionByProductId($id){
		if($id>0):
			$collection = $this->getDesignersoftwareCollection()
									->addFieldToFilter('product_id', $id)
									->addFieldToFilter('status',1)
									->getFirstItem();
		endif;
			
		return $collection;
	}
	
	public function getCollectionBySession($sessionId){
		if(!empty($sessionId)):
			$collection = $this->getDesignersoftwareCollection()
									->addFieldToFilter('session_id', $sessionId)
									->addFieldToFilter('status',1);									
		endif;
			
		return $collection;
	}
}
