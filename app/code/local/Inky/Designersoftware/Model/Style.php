<?php

class Inky_Designersoftware_Model_Style extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('designersoftware/style');
    }
    
    public function getStyleCollection(){
		$collection =  Mage::getModel('designersoftware/style')->getCollection()
									->addFieldToFilter('status',1);
		
		
		return $collection;
	}
}
