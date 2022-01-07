<?php

class Inky_Designersoftware_Model_Mysql4_Font extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the font_id refers to the key field in your database table.
        $this->_init('designersoftware/font', 'font_id');
    }
}
