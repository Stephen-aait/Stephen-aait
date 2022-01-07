<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */
class Amasty_Orderattach_Block_Adminhtml_Field_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('amorderattach_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('amorderattach')->__('Field Configuration'));
    }
    
    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('amorderattach')->__('General Information'),
            'title'     => Mage::helper('amorderattach')->__('General Information'),
            'content'   => $this->getLayout()->createBlock('amorderattach/adminhtml_field_edit_tab_main')->toHtml(),
        ));
        
        $this->addTab('visibility_section', array(
            'label'     => Mage::helper('amorderattach')->__('Order Status Visibility'),
            'title'     => Mage::helper('amorderattach')->__('Order Status Visibility'),
            'content'   => $this->getLayout()->createBlock('amorderattach/adminhtml_field_edit_tab_visibility')->toHtml(),
        ));
        return parent::_beforeToHtml();
    }
}