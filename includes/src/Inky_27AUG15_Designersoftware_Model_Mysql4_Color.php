<?php

class Inky_Designersoftware_Model_Mysql4_Color extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the color_id refers to the key field in your database table.
        $this->_init('designersoftware/color', 'color_id');
    }
}
