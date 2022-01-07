<?php

class Inky_Designersoftware_Model_Font extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('designersoftware/font');
    }
    
    public function getFontCollection(){
		$collection =  Mage::getModel('designersoftware/font')->getCollection()
									->addFieldToFilter('status',1);
		
		
		return $collection;
	}
}
