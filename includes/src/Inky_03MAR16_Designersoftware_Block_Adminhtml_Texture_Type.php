<?php
class Inky_Designersoftware_Block_Adminhtml_Texture_type extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {	
    $this->_controller = 'adminhtml_texture_type';
    $this->_blockGroup = 'designersoftware';
    $this->_headerText = Mage::helper('designersoftware')->__('Texture Type Manager');
    $this->_addButtonLabel = Mage::helper('designersoftware')->__('Add Texturetype');
    
    parent::__construct();    
  }
}
