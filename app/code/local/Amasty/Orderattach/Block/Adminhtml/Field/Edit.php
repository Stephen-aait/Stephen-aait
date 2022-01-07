<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */
class Amasty_Orderattach_Block_Adminhtml_Field_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId   = 'field_id';
        $this->_blockGroup = 'amorderattach';
        $this->_controller = 'adminhtml_field';

        parent::__construct();

        $this->_updateButton('save',   'label', Mage::helper('amorderattach')->__('Save Field'));
        $this->_updateButton('delete', 'label', Mage::helper('amorderattach')->__('Delete Field'));
    }
    
    public function getHeaderText()
    {
        if (Mage::registry('amorderattach_field')->getId()) {
            return Mage::helper('amorderattach')->__("Edit Field `%s`", $this->htmlEscape(Mage::registry('amorderattach_field')->getLabel()));
        }
        else {
            return Mage::helper('amorderattach')->__('New Field');
        }
    }
}