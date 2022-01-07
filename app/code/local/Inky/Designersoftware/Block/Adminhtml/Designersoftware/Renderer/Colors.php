<?php
class Inky_Designersoftware_Block_Adminhtml_Designersoftware_Renderer_Colors extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $data = $row->getData();       
		
		$colorUsedString = Mage::helper('designersoftware')->getUsedColorsString($data['chinese_info_arr']);
		
		return $colorUsedString;
    }
}
?>
