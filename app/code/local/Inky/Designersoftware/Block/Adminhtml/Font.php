<?php
class Inky_Designersoftware_Block_Adminhtml_Font extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {	
    $this->_controller = 'adminhtml_font';
    $this->_blockGroup = 'designersoftware';
    $this->_headerText = Mage::helper('designersoftware')->__('Font Manager');
    $this->_addButtonLabel = Mage::helper('designersoftware')->__('Add Font');
    
    parent::__construct();  
    //$this->_removeButton('add');  
  }
}
