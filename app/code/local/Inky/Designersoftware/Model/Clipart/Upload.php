<?php

class Inky_Designersoftware_Model_Clipart_Upload extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('designersoftware/clipart_upload');
    }
    
    public function getClipartUploadCollection(){
		$collection =  Mage::getModel('designersoftware/clipart_upload')->getCollection()
									->addFieldToFilter('status',1);
		
		
		return $collection;
	}
	
	public function getCollectionByCustomer(){
		$currentCustomerId = Mage::helper('designersoftware/customer')->getCurrentCustomerId();		
		if($currentCustomerId>0):
			$collection = $this->getClipartUploadCollection()
								->addFieldToFilter('customer_id',$currentCustomerId);
			return $collection;
		endif;
		
		$currentSessionId = Mage::helper('designersoftware/customer')->getCurrentSessionId();
		if(!empty($currentSessionId)>0):
			$collection = $this->getClipartUploadCollection()
								->addFieldToFilter('session_id',$currentSessionId);
			
			if($collection->getSize()>0):
				return $collection;
			endif;
		endif;							
	}
}
