<?php

class Inky_Designersoftware_Model_Mysql4_Parts_Style extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the parts_style_id refers to the key field in your database table.
        $this->_init('designersoftware/parts_style', 'parts_style_id');
    }
}
