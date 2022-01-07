<?php

/**
 * MageWorx
 * Loyalty Booster Extension
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @copyright  Copyright (c) 2016 MageWorx (http://www.mageworx.com/)
 */
class A2bizz_Customerdesign_Block_Adminhtml_Customer_Edit_Tab_Customerdesign_Accordion extends Mage_Adminhtml_Block_Widget_Accordion
{
    protected function _prepareLayout()
    {
        $this->setId('customerdesignAccordion');
        
        $this->addItem('log', array(
            'title'       => Mage::helper('customerdesign')->__('Customer Design'),
            'ajax'        => true,
            'content_url' => $this->getUrl('adminhtml/customerdesign/grid', array('_current' => true)),
        ));
    }
}
