<?php
class Inky_Designersoftware_Block_Designersoftware_View extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    } 
    
    public function getCollection(){
		$collection = Mage::registry('current_design');
		return $collection;
	}  
}
