<?php

class Modulesgarden_Teamandtaskorganizer_Model_Task_Events_AfterReview extends Modulesgarden_Teamandtaskorganizer_Model_Task_Event {
    protected $type = 'after_review';
    protected $name = 'After New Product Review Added';
    protected $vars = array('review_id', 'title', 'detail', 'nickname', 'customer_id', 'product_id');
    protected $foreignType = 'review';
    
    public function isValid($obj) {
        return ($obj instanceof Mage_Review_Model_Review);
    }
}