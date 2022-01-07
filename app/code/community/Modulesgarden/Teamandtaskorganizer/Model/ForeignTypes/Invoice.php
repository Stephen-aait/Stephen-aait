<?php

class Modulesgarden_Teamandtaskorganizer_Model_ForeignTypes_Invoice extends Modulesgarden_Teamandtaskorganizer_Model_Foreign {
    
    public function getUrl() {
        return Mage::helper('adminhtml')->getUrl('adminhtml/sales_invoice/view', array(
            'invoice_id' => $this->getForeignId()
        ));
    }
    
    public function getName() {
        return Mage::helper('teamandtaskorganizer')->__('Invoice #%s', $this->task->getForeignId());
    }
    
} 