<?php
class Inky_Designersoftware_Block_Adminhtml_Sizes extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {	
    $this->_controller = 'adminhtml_sizes';
    $this->_blockGroup = 'designersoftware';
    $this->_headerText = Mage::helper('designersoftware')->__('Sizes Manager');
    $this->_addButtonLabel = Mage::helper('designersoftware')->__('Add Sizes');
    
    parent::__construct();  
    //$this->_removeButton('add');  
  }
}
