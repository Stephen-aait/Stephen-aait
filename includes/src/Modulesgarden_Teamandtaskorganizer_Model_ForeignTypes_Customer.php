<?php

class Modulesgarden_Teamandtaskorganizer_Model_ForeignTypes_Customer extends Modulesgarden_Teamandtaskorganizer_Model_Foreign {
    
    public function getUrl() {
        return Mage::helper('adminhtml')->getUrl('adminhtml/customer/edit', array(
            'id' => $this->getForeignId()
        ));
    }
    
    public function getName() {
        return Mage::helper('teamandtaskorganizer')->__('Customer #%s', $this->task->getForeignId());
    }

} 