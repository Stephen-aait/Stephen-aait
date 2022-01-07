<?php
class Inky_Designersoftware_Block_Adminhtml_Parts_Layers_Texture extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {	
    $this->_controller = 'adminhtml_parts_layers_texture';
    $this->_blockGroup = 'designersoftware';
    $this->_headerText = Mage::helper('designersoftware')->__('Layers Texture Manager');
    $this->_addButtonLabel = Mage::helper('designersoftware')->__('Add Layers Texture');
    
    parent::__construct();    
  }
}
