<?php

class Sparx_Designersoftware_Model_Mysql4_Parts_Type extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the parts_id refers to the key field in your database table.
        $this->_init('designersoftware/parts_type', 'parts_type_id');
    }
}
