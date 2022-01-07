<?php

class Modulesgarden_Teamandtaskorganizer_Model_ForeignTypes_Order extends Modulesgarden_Teamandtaskorganizer_Model_Foreign {
    
    public function getUrl() {
        return Mage::helper('adminhtml')->getUrl('adminhtml/sales_order/view',array(
            'order_id' => $this->task->getForeignId()
        ));
    }
    
    public function getName() {
        return Mage::helper('teamandtaskorganizer')->__('Order #%s', $this->task->getForeignId());
    }
    
} 