<?php
class Inky_Designersoftware_Block_Adminhtml_Text extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {		  
    $this->_controller = 'adminhtml_text';
    $this->_blockGroup = 'designersoftware';
    $this->_headerText = Mage::helper('designersoftware')->__('Text Manager');
    $this->_addButtonLabel = Mage::helper('designersoftware')->__('Add Text Pricing');
    
    parent::__construct();  
    //$this->_removeButton('add');  
  }
}
