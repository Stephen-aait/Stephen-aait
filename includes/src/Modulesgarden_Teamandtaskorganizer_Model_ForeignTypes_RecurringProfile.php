<?php

class Modulesgarden_Teamandtaskorganizer_Model_ForeignTypes_RecurringProfile extends Modulesgarden_Teamandtaskorganizer_Model_Foreign {
    
    public function getUrl() {
        return Mage::helper('adminhtml')->getUrl('adminhtml/sales_recurring_profile/view', array(
            'profile' => $this->getForeignId()
        ));
    }
    
    public function getName() {
        return Mage::helper('teamandtaskorganizer')->__('Recurring Profile #%s', $this->task->getForeignId());
    }
} 