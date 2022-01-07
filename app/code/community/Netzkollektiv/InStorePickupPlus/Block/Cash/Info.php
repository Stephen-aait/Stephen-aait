<?php
class Netzkollektiv_InStorePickupPlus_Block_Cash_Info extends Mage_Payment_Block_Info {
    protected function _construct() {
        parent::_construct();
        $this->setTemplate('instorepickupplus/cash/info.phtml');
    }
}