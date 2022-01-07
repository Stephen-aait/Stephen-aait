<?php
class Inky_Designersoftware_Block_Adminhtml_Designersoftware_Renderer_Category extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $data = $row->getData();       
		
		$categoryName = Mage::helper('designersoftware/category')->getCategoryNameById($data['category_id']);
		//echo '<pre>';print_r($data['category_id']);exit;
		
		return $categoryName;
    }
}
?>
