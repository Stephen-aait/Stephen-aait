<?php

class Modulesgarden_Teamandtaskorganizer_Model_ForeignTypes_Review extends Modulesgarden_Teamandtaskorganizer_Model_Foreign {
    
    public function getUrl() {
        return Mage::helper('adminhtml')->getUrl('adminhtml/catalog_product_review/edit', array(
            'id' => $this->getForeignId()
        ));
    }
    
    public function getName() {
        return Mage::helper('teamandtaskorganizer')->__('Review #%s', $this->task->getForeignId());
    }
} 