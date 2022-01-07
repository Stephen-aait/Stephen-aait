<?php

/**
 * MageWorx
 * Loyalty Booster Extension
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @copyright  Copyright (c) 2016 MageWorx (http://www.mageworx.com/)
 */
class A2bizz_Customerdesign_Block_Adminhtml_Customer_Edit_Tab_Customerdesign
 extends Mage_Adminhtml_Block_Template
 implements Mage_Adminhtml_Block_Widget_Tab_Interface {
    
    public function __construct() {
        parent::__construct();
        $this->setId('customerdesign');
    }

    public function getAfter() {
        return 'tags';
    }

    public function getTabLabel() {
        return Mage::helper('customerdesign')->__('Customer Designs');
    }

    public function getTabTitle() {
        return Mage::helper('customerdesign')->__('Customer Designs');
    }

    public function canShowTab() {
        return true;
    }

    public function isHidden() {
        if (!Mage::helper('customerdesign')->isShowCustomerdesign()) {
            return true;
        }
        if (Mage::registry('current_customer')->getId()) {
            return false;
        }
        return true;
    }

    protected function _toHtml() {
        return parent::_toHtml() . $this->getChildHtml();
    }
}
