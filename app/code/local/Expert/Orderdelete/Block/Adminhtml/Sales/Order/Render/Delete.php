<?php 
/**
 * @author      Sharad Patel
 * @category    Sale
 * @package     Expert_Orderdelete
 * @copyright   Copyright (c) 2013 expertsofttechsolution.com 
 */
class Expert_Orderdelete_Block_Adminhtml_Sales_Order_Render_Delete extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
		$getData = $row->getData();
		$message = Mage::helper('sales')->__('Do you want to Delete this Order?');
		$orderID = $getData['entity_id'];
        $view = $this->getUrl('*/sales_order/view',array('order_id' => $orderID));
		$delete = $this->getUrl('orderdelete/adminhtml_orderdelete/delete',array('order_id' => $orderID));
		
		$configValue = Mage::getStoreConfig('sales/orderdelete/enabled');
		if($configValue==0){
		$link = '<a href="'.$view.'">View</a><br />';
		}
		else{
		$link = '<a href="'.$view.'">View</a><br />
				<a href="#" onclick="deleteConfirm(\''.$message.'\', \'' . $delete . '\')">Delete Order</a>';
				
		}
		return $link;

		}
    }

?>
