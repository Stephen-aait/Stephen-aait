<?php

class Inky_Designersoftware_Model_Mysql4_Orders_Design extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the parts_id refers to the key field in your database table.
        $this->_init('designersoftware/orders_design', 'orders_design_id');
    }
}
