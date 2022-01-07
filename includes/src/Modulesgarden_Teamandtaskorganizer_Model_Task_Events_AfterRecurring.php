<?php

class Modulesgarden_Teamandtaskorganizer_Model_Task_Events_AfterRecurring extends Modulesgarden_Teamandtaskorganizer_Model_Task_Event {
    protected $type = 'after_recurring';
    protected $name = 'After New Recurring Profile Order';
    protected $vars = array('profile_id', 'customer_id', 'schedule_description', 'state');
    protected $foreignType = 'recurringprofile';
    
    public function isValid($obj) {
        return ($obj instanceof Mage_Payment_Model_Recurring_Profile);
    }
}