<?php

class Modulesgarden_Teamandtaskorganizer_Model_Task_Events_AfterInvoice extends Modulesgarden_Teamandtaskorganizer_Model_Task_Event {
    protected $type = 'after_invoice';
    protected $name = 'After Creating New Invoice';
    protected $vars = array('entity_id', 'store_id', 'grand_total', 'subtotal', 'order_id', 'customer_id');
    protected $foreignType = 'invoice';
    
    public function isValid($obj) {
        return ($obj instanceof Mage_Sales_Model_Order_Invoice);
    }
}