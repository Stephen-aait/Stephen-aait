<?php

class Inky_Designersoftware_Model_Mysql4_Clipart_Category extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the clipart_category_id refers to the key field in your database table.
        $this->_init('designersoftware/clipart_category', 'clipart_category_id');
    }
}
