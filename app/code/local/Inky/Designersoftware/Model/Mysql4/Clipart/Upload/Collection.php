<?php

class Inky_Designersoftware_Model_Mysql4_Clipart_Upload_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('designersoftware/clipart_upload');
    }
}
