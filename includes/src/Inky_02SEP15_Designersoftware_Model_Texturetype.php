<?php

class Sparx_Designersoftware_Model_Leathertype extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('designersoftware/leathertype');
    }
    
    public function getCollectionById($id){
		//return $id;
		$collection = Mage::getModel('designersoftware/leathertype')->getCollection()
								->addFieldToFilter('leathertype_id', $id)
								->addFieldToFilter('status',1)
								->getFirstItem();
		
		return $collection;
	}
	
	public function getDataCollectionById($id){
		//return $id;
		$collection = Mage::getModel('designersoftware/leathertype')->getCollection()
								->addFieldToFilter('leathertype_id', $id)
								->addFieldToFilter('status',1)
								->getFirstItem()
								->getData();
		
		return $collection;
	}
    
    public function getTitle($id){
		//return $id;
		$collection = Mage::getModel('designersoftware/leathertype')->getCollection()
								->addFieldToFilter('leathertype_id', $id)
								->addFieldToFilter('status',1)
								->getFirstItem();
		
		return $collection->getTitle();
	}
	
	public function getLeathertypeCollection(){
		$collection = Mage::getModel('designersoftware/leathertype')->getCollection()
										->addFieldToFilter('status',1)
										->setOrder('sort_order','ASC');
												
		return $collection;
	}
}
