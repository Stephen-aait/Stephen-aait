<?php

class Inky_Designersoftware_Model_Mysql4_Text extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the angles_id refers to the key field in your database table.
        $this->_init('designersoftware/text', 'text_id');
    }
}
