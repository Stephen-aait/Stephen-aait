<?php

class Inky_Designersoftware_Model_Clipart_Category extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('designersoftware/clipart_category');
    }
    
    public function getClipartCategoryCollection(){
		$collection =  Mage::getModel('designersoftware/clipart_category')->getCollection()
									->addFieldToFilter('status',1);
		
		
		return $collection;
	}
}
