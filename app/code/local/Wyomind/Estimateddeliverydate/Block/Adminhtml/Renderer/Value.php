<?php

class Wyomind_Estimateddeliverydate_Block_Adminhtml_Renderer_Value extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action {

    public function render(Varien_Object $row) {
        return Mage::getModel('eav/entity_attribute_option')
                ->getCollection()
                ->setStoreFilter()
                ->join('attribute', 'attribute.attribute_id=main_table.attribute_id', 'attribute_code')
                ->addFieldToFilter('main_table.option_id', array('eq' => $row->getValueId()))
                ->getFirstItem()->getValue();
        
    }

}
