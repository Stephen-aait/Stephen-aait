<?php
class Sparx_Designersoftware_Block_Adminhtml_Parts_Type extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {	
    $this->_controller = 'adminhtml_parts_type';
    $this->_blockGroup = 'designersoftware';
    $this->_headerText = Mage::helper('designersoftware')->__('Type Manager');
    $this->_addButtonLabel = Mage::helper('designersoftware')->__('Add Type');
    
    parent::__construct();    
  }
}
