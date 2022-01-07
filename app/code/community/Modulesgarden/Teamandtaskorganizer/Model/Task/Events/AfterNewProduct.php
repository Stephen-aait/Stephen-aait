<?php

class Modulesgarden_Teamandtaskorganizer_Model_Task_Events_AfterNewProduct extends Modulesgarden_Teamandtaskorganizer_Model_Task_Event {
    protected $type = 'after_new_product';
    protected $name = 'After Adding New Product';
    protected $vars = array('entity_id', 'sku', 'type_id');
    protected $foreignType = 'product';
    
    public function isValid($obj) {
        return ($obj instanceof Mage_Catalog_Model_Product);
    }
}