<?php

class Inky_Designersoftware_Model_Clipart_Price extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('designersoftware/clipart_price');
    }
    
    public function getClipartPriceCollection(){
		$collection =  Mage::getModel('designersoftware/clipart_price')->getCollection()
									->addFieldToFilter('status',1);
		
		
		return $collection;
	}
}
