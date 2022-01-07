<?php

class Modulesgarden_Teamandtaskorganizer_Model_Task_Events_AfterCustomer extends Modulesgarden_Teamandtaskorganizer_Model_Task_Event {
    protected $type = 'after_customer';
    protected $name = 'After Creating New Customer';
    protected $vars = array('entity_id', 'email', 'group_id', 'firstname', 'lastname');
    protected $foreignType = 'customer';
    
    public function isValid($obj) {
        return ($obj instanceof Mage_Customer_Model_Customer);
    }
}