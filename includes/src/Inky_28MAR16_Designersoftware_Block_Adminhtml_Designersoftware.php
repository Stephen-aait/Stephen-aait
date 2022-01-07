<?php
class Inky_Designersoftware_Block_Adminhtml_Designersoftware extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_designersoftware';
    $this->_blockGroup = 'designersoftware';
    $this->_headerText = Mage::helper('designersoftware')->__('Design Manager');
    $this->_addButtonLabel = Mage::helper('designersoftware')->__('Add Design');
    
    parent::__construct();  
    $this->_removeButton('add');  
  }
}
