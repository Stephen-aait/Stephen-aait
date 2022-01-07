<?php

abstract class Modulesgarden_Teamandtaskorganizer_Model_Foreign {
    protected $task;
    
    public function __construct(Modulesgarden_Teamandtaskorganizer_Model_Task $task) {
        $this->task = $task;
    }
    
    public static function make(Modulesgarden_Teamandtaskorganizer_Model_Task $task) {
        switch( $task->getForeignType() ) {
            case 'order': 
                return new Modulesgarden_Teamandtaskorganizer_Model_ForeignTypes_Order($task);
            case 'customer': 
                return new Modulesgarden_Teamandtaskorganizer_Model_ForeignTypes_Customer($task);
            case 'invoice': 
                return new Modulesgarden_Teamandtaskorganizer_Model_ForeignTypes_Invoice($task);
            case 'shipment': 
                return new Modulesgarden_Teamandtaskorganizer_Model_ForeignTypes_Shipment($task);
            case 'review': 
                return new Modulesgarden_Teamandtaskorganizer_Model_ForeignTypes_Review($task);
            case 'product': 
                return new Modulesgarden_Teamandtaskorganizer_Model_ForeignTypes_Product($task);
            case 'recurringprofile': 
                return new Modulesgarden_Teamandtaskorganizer_Model_ForeignTypes_RecurringProfile($task);
            default:
                return null;
        }
    }
    
    public abstract function getUrl();
    public abstract function getName();
}