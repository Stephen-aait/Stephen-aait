<?php

class Modulesgarden_Teamandtaskorganizer_Model_Task_Events_AfterChangedOrderStatus extends Modulesgarden_Teamandtaskorganizer_Model_Task_Event {
    protected $type = 'after_changed_order_status';
    protected $name = 'After Changed Order Status';
    protected $vars = array('store_id', 'entity_id', 'state', 'status', 'customer_id', 'customer_email', 'customer_firstname', 'customer_lastname');
    protected $foreignType = 'order';
    
    public function isValid($obj) {
        return ($obj instanceof Mage_Sales_Model_Order);
    }
}