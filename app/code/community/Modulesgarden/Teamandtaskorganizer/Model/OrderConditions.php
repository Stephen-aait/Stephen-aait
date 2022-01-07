<?php

class Modulesgarden_Teamandtaskorganizer_Model_OrderConditions {
    protected $primary;
    protected $target;
    
    const ORDER_CONDITION_STATUS_ANY = 'any';
    
    public function __construct($primary, $target) {
        $this->primary  = $primary;
        $this->target   = $target;
    }
    
    public function isMatchingOrder(Mage_Sales_Model_Order $order) {
        $oldStatus  = $order->getOrigData('status');
        $newStatus  = $order->getData('status');
        
        if($oldStatus === $newStatus) {
            return false;
        }
        
        if( $this->isPrimaryAny() AND $this->isTargetAny() ) {
            return true;
        }
        elseif($this->getPrimary() === $oldStatus AND $this->isTargetAny() ) {
            return true;
        }
        elseif($this->getTarget() === $newStatus AND $this->isPrimaryAny() ) {
            return true;
        }
        elseif($this->getPrimary() === $oldStatus AND $this->getTarget() === $newStatus) {
            return true;
        }
        
        return false;
    }
    
    public function getPrimary() {
        return $this->primary;
    }
    
    public function getTarget() {
        return $this->target;
    }
    
    public function isPrimaryAny() {
        return $this->getPrimary() === static::ORDER_CONDITION_STATUS_ANY;
    }
    
    public function isTargetAny() {
        return $this->getTarget() === static::ORDER_CONDITION_STATUS_ANY;
    }
    
}