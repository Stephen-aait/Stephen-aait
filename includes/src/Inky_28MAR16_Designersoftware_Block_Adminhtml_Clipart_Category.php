<?php
class Inky_Designersoftware_Block_Adminhtml_Clipart_Category extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {	
    $this->_controller = 'adminhtml_clipart_category';
    $this->_blockGroup = 'designersoftware';
    $this->_headerText = Mage::helper('designersoftware')->__('Clipart Category Manager');
    $this->_addButtonLabel = Mage::helper('designersoftware')->__('Add Clipart Category');
    
    parent::__construct();  
    //$this->_removeButton('add');  
  }
}
