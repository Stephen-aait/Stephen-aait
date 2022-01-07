<?php

class Inky_Designersoftware_Model_Mysql4_Texture extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the texture_id refers to the key field in your database table.
        $this->_init('designersoftware/texture', 'texture_id');
    }
}
