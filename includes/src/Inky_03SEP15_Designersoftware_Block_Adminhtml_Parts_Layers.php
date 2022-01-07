<?php
class Inky_Designersoftware_Block_Adminhtml_Parts_Layers extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {	
    $this->_controller = 'adminhtml_parts_layers';
    $this->_blockGroup = 'designersoftware';
    $this->_headerText = Mage::helper('designersoftware')->__('Layers Manager');
    $this->_addButtonLabel = Mage::helper('designersoftware')->__('Add Layers');
    
    parent::__construct();    
  }
}
