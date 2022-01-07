<?php

class Modulesgarden_Teamandtaskorganizer_Model_Task_Events_AfterShip extends Modulesgarden_Teamandtaskorganizer_Model_Task_Event {
    protected $type = 'after_ship';
    protected $name = 'After Creating New Shipment';
    protected $vars = array('entity_id', 'store_id', 'total_weight', 'total_qty', 'order_id', 'customer_id');
    protected $foreignType = 'shipment';
    
    public function isValid($obj) {
        return ($obj instanceof Mage_Sales_Model_Order_Shipment);
    }
}