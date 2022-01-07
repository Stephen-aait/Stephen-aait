<?php

abstract class Modulesgarden_Teamandtaskorganizer_Model_Task_Event {
    protected $type;
    protected $name;
    protected $vars = array();
    protected $foreignType;
    
    public static function getTypes() {
        return array(
            'after_order'                   => new Modulesgarden_Teamandtaskorganizer_Model_Task_Events_AfterOrder,
            'after_recurring'               => new Modulesgarden_Teamandtaskorganizer_Model_Task_Events_AfterRecurring,
            'after_customer'                => new Modulesgarden_Teamandtaskorganizer_Model_Task_Events_AfterCustomer,
            'after_invoice'                 => new Modulesgarden_Teamandtaskorganizer_Model_Task_Events_AfterInvoice,
            'after_ship'                    => new Modulesgarden_Teamandtaskorganizer_Model_Task_Events_AfterShip,
            'after_review'                  => new Modulesgarden_Teamandtaskorganizer_Model_Task_Events_AfterReview,
            'after_new_product'             => new Modulesgarden_Teamandtaskorganizer_Model_Task_Events_AfterNewProduct,
            'after_changed_order_status'    => new Modulesgarden_Teamandtaskorganizer_Model_Task_Events_AfterChangedOrderStatus,
        );
    }
    
    public static function make($event) {
        $types = static::getTypes();
        
        if( array_key_exists($event, $types) ) {
            return $types[$event];
        }
        
        return null;
    }
    
    public function getType() {
        return $this->type;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getVars() {
        return $this->vars;
    }
    
    public function getForeignType() {
        return $this->foreignType;
    }
    
    public abstract function isValid($obj);
}