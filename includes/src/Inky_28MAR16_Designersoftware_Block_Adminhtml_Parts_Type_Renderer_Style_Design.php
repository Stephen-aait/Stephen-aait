<?php
class Sparx_Designersoftware_Block_Adminhtml_Parts_Layers_Renderer_Style_Design extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $data = $row->getData();
                
        //echo '<pre>';print_r($data);exit;
		$codeString=Mage::helper('designersoftware/style_design')->getStyleDesignCodes($data['style_design_ids']);		
		return $codeString;
    }
}
?>
