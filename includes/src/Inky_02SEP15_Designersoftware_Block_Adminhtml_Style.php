<?php
class Inky_Designersoftware_Block_Adminhtml_Style extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {	
    $this->_controller = 'adminhtml_style';
    $this->_blockGroup = 'designersoftware';
    $this->_headerText = Mage::helper('designersoftware')->__('Style Manager');
    $this->_addButtonLabel = Mage::helper('designersoftware')->__('Add Style');
    
    parent::__construct();    
  }
}
