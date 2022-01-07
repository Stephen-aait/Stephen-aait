<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */
class Amasty_Orderattach_Model_Field extends Mage_Core_Model_Abstract
{
    public function getRenderer($type = 'backend')
    {
        if ('backend' == $type) {
            $blockName = 'amorderattach/adminhtml_sales_order_view_attachment_renderer_' . strtolower($this->getType());
        } else {
            $blockName = 'amorderattach/sales_order_view_attachment_renderer_' . strtolower($this->getType());
        }
        $renderer = Mage::app()->getLayout()->createBlock($blockName, 'amorderattach.renderer.' . strtolower($this->getType()));
        $renderer->setAttachmentField($this);

        return $renderer;
    }

    public function getItemCode($itemId)
    {
        return 'amProduct_' . $itemId . '_' . $this->getData('code');
    }

    protected function _construct()
    {
        $this->_init('amorderattach/field');
    }

    protected function _beforeSave()
    {
        if (!$this->getId())
            Mage::register('amorderattach_add_field', true, true);

        return parent::_beforeSave();
    }
    
    protected function _afterSave()
    {
        if (Mage::registry('amorderattach_add_field')) {
            $data = array(
                          'code' => $this->getCode(),
                          'type'      => $this->getType(),
                          'apply_to_each_product'      => $this->getApplyToEachProduct()
                          );
            Mage::register('amorderattach_additional_data', $data);
        }
        return parent::_afterSave();
    }

    protected function _afterDeleteCommit()
    {
        Mage::getModel('amorderattach/order_field')->deleteField($this->getCode());
        parent::_afterDeleteCommit();
        Mage::helper('amorderattach')->clearCache();
        return $this;
    }
}
