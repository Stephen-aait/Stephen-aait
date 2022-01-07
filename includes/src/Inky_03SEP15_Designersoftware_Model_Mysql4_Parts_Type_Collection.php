<?php

class Sparx_Designersoftware_Model_Mysql4_Parts_Type_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('designersoftware/parts_type');
    }
}
