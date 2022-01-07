<?php
class Inky_Designersoftware_Block_Adminhtml_Designersoftware_Renderer_Customer extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $data = $row->getData();       
		
		$customerName = Mage::helper('designersoftware/customer')->getCustomerNameById($data['customer_id']);
		//echo '<pre>';print_r($data['customer_id']);exit;
		
		return $customerName;
    }
}
?>
