<?php
class Inky_Designersoftware_Block_Adminhtml_Texture extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {	
    $this->_controller = 'adminhtml_texture';
    $this->_blockGroup = 'designersoftware';
    $this->_headerText = Mage::helper('designersoftware')->__('Texture Manager');
    $this->_addButtonLabel = Mage::helper('designersoftware')->__('Add Texture');
    
    parent::__construct();    
  }
}
