<?php
class Inky_Designersoftware_Block_Adminhtml_Heel extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {	
    $this->_controller = 'adminhtml_heel';
    $this->_blockGroup = 'designersoftware';
    $this->_headerText = Mage::helper('designersoftware')->__('Heel Manager');
    $this->_addButtonLabel = Mage::helper('designersoftware')->__('Add Heel');
    
    parent::__construct();    
  }
}
