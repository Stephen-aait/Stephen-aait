<?php

class Inky_Designersoftware_Model_Mysql4_Sizes extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the sizes_id refers to the key field in your database table.
        $this->_init('designersoftware/sizes', 'sizes_id');
    }
}
