<?php
/**
 * @author      Sharad Patel
 * @category    Sale
 * @package     Expert_Orderdelete
 * @copyright   Copyright (c) 2013 expertsofttechsolution.com 
 */
class Expert_Orderdelete_Block_Orderdelete extends Mage_Core_Block_Template
{
	
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getOrderdelete()     
     { 
	 $a=1;
	 if($a==1){
        if (!$this->hasData('orderdelete')) {
            $this->setData('orderdelete', Mage::registry('orderdelete'));
        }
        return $this->getData('orderdelete');
	 }
    }
}