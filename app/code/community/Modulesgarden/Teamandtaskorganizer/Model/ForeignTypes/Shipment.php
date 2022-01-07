<?php

class Modulesgarden_Teamandtaskorganizer_Model_ForeignTypes_Shipment extends Modulesgarden_Teamandtaskorganizer_Model_Foreign {
    
    public function getUrl() {
        return Mage::helper('adminhtml')->getUrl('adminhtml/sales_shipment/view', array(
            'shipment_id' => $this->getForeignId()
        ));
    }
    
    public function getName() {
        return Mage::helper('teamandtaskorganizer')->__('Shipment #%s', $this->task->getForeignId());
    }
} 