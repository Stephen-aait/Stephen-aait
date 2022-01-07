<?php
/**
 * @author      Sharad Patel
 * @category    Sale
 * @package     Expert_Orderdelete
 * @copyright   Copyright (c) 2013 expertsofttechsolution.com 
 */

class Expert_Orderdelete_Block_Adminhtml_Orderdelete extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
	if (Mage::getStoreConfigFlag('advanced/modules_disable_output/Expert_Orderdelete'))  {
    $this->_controller = 'adminhtml_orderdelete';
    $this->_blockGroup = 'orderdelete';
    $this->_headerText = Mage::helper('orderdelete')->__('Delete Order');}
   // $this->_addButtonLabel = Mage::helper('orderdelete')->__('Add Item');
    parent::__construct();
	}
  
}