<?php
class Sparx_Designersoftware_Block_Adminhtml_Parts_Layers_Renderer_Parts extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $data = $row->getData();
                
		$collection=Mage::getModel('designersoftware/parts')->getPartsCollection($data['parts_id']);		
		return $collection['title'];
    }
}
?>
