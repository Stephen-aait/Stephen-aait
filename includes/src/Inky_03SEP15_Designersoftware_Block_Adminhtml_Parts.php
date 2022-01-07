<?php
class Inky_Designersoftware_Block_Adminhtml_Parts extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {	
    $this->_controller = 'adminhtml_parts';
    $this->_blockGroup = 'designersoftware';
    $this->_headerText = Mage::helper('designersoftware')->__('Parts Manager');
    $this->_addButtonLabel = Mage::helper('designersoftware')->__('Add Parts');
    
    parent::__construct();  
    $this->_removeButton('add');  
  }
}
