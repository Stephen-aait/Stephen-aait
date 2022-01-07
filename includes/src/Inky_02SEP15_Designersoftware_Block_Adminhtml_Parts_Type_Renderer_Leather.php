<?php
class Sparx_Designersoftware_Block_Adminhtml_Parts_Layers_Renderer_Leather extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $data = $row->getData();
        
        $collection=Mage::getModel('designersoftware/leather')->getLeatherCollection($data['leather_id']);			       
        return $collection['title'];
    }
}
?>
