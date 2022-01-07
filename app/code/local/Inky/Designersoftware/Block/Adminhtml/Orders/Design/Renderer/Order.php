<?php

class Inky_Designersoftware_Block_Adminhtml_Orders_Design_Renderer_Order extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $data = $row->getData();
        $orderid = $data['order_id'];
        $order = Mage::getModel('sales/order')->load($orderid);
		$Incrementid = $order->getIncrementId();
		
		$html ='<a href="'. Mage::helper('adminhtml')->getUrl('adminhtml/sales_order/view',array('order_id'=>$data["order_id"])).'">'.$Incrementid.'</a>';
		
		return $html;
    }
}

?>
