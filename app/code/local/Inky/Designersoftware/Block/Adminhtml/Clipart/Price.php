<?php
class Inky_Designersoftware_Block_Adminhtml_Clipart_Price extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {	
    $this->_controller = 'adminhtml_clipart_price';
    $this->_blockGroup = 'designersoftware';
    $this->_headerText = Mage::helper('designersoftware')->__('Size Price Manager');    
    $this->_addButtonLabel = Mage::helper('designersoftware')->__('Add New');
    
    parent::__construct();  
    //$this->_removeButton('add');  
  }
}
