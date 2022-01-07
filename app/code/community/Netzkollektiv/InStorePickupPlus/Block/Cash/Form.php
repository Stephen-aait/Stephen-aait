<?php
class Netzkollektiv_InStorePickupPlus_Block_Cash_Form extends Mage_Payment_Block_Form {
    protected function _construct() {
        parent::_construct();
        $this->setTemplate('instorepickupplus/cash/form.phtml');
    }
}