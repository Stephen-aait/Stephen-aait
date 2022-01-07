<?php 
/**
 * @author      Sharad Patel
 * @category    Sale
 * @package     Expert_Orderdelete
 * @copyright   Copyright (c) 2013 expertsofttechsolution.com 
 */
 
 
class Expert_Orderdelete_Block_Adminhtml_Sales_Order_View extends Mage_Adminhtml_Block_Sales_Order_View 
{   
	
	public function  __construct() {
		parent::__construct();
		$configValue = Mage::getStoreConfig('sales/orderdelete/enabled');
		if($configValue==1){
		$message = Mage::helper('sales')->__('Are you sure you want to Delete this order?');
        $this->_addButton('button_id', array(
            'label'     => Mage::helper('Sales')->__('Delete Order'),
            'onclick'   => 'deleteConfirm(\''.$message.'\', \'' . $this->getDeleteUrl() . '\')',
            'class'     => 'go'
        ), 0, 100, 'header', 'header');
    }
	}
	
    public function getDeleteUrl()
    {
        return $this->getUrl('orderdelete/adminhtml_orderdelete/delete', array('_current'=>true));
    }	
}
?>