<?php
class Inky_Designersoftware_Block_Adminhtml_Category extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {	
    $this->_controller = 'adminhtml_category';
    $this->_blockGroup = 'designersoftware';
    $this->_headerText = Mage::helper('designersoftware')->__('Category Manager');
    $this->_addButtonLabel = Mage::helper('designersoftware')->__('Add Category');
    
    parent::__construct();  
    //$this->_removeButton('add');  
  }
}
