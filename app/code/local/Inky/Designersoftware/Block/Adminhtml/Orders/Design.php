<?php
class Inky_Designersoftware_Block_Adminhtml_Orders_Design extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_orders_design';
    $this->_blockGroup = 'designersoftware';
    $this->_headerText = Mage::helper('designersoftware')->__('Ordered Designs');
    $this->_addButtonLabel = Mage::helper('designersoftware')->__('Add Design');
    
    parent::__construct();  
    $this->_removeButton('add');  
  }
}
