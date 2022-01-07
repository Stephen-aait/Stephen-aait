<?php
class Inky_Designersoftware_Block_Adminhtml_Color extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {	
    $this->_controller = 'adminhtml_color';
    $this->_blockGroup = 'designersoftware';
    $this->_headerText = Mage::helper('designersoftware')->__('Color Manager');
    $this->_addButtonLabel = Mage::helper('designersoftware')->__('Add Color');
    parent::__construct();    
    
    //$this->_removeButton('add');
  }
}
