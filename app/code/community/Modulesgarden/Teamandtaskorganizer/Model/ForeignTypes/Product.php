<?php

class Modulesgarden_Teamandtaskorganizer_Model_ForeignTypes_Product extends Modulesgarden_Teamandtaskorganizer_Model_Foreign {
    
    public function getUrl() {
        return Mage::helper('adminhtml')->getUrl('adminhtml/catalog_product/edit', array(
            'id' => $this->getForeignId()
        ));
    }
    
    public function getName() {
        return Mage::helper('teamandtaskorganizer')->__('Product #%s', $this->task->getForeignId());
    }
} 