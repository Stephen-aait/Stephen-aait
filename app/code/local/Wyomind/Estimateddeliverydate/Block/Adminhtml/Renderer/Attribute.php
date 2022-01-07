<?php

class Wyomind_Estimateddeliverydate_Block_Adminhtml_Renderer_Attribute extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action {

    public function render(Varien_Object $row) {
       echo Mage::getModel('catalog/resource_eav_attribute')->load($row->getAttributeId())->getFrontendLabel();
        
    }

}
