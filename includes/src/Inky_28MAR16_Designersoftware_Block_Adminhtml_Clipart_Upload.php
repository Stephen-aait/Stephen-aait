<?php
class Inky_Designersoftware_Block_Adminhtml_Clipart_Upload extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {	
    $this->_controller = 'adminhtml_clipart_upload';
    $this->_blockGroup = 'designersoftware';
    $this->_headerText = Mage::helper('designersoftware')->__('Clipart Upload Manager');
    $this->_addButtonLabel = Mage::helper('designersoftware')->__('Add Clipart Upload');
    
    parent::__construct();  
    //$this->_removeButton('add');  
  }
}
