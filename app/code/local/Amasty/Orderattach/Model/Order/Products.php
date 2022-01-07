<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */
class Amasty_Orderattach_Model_Order_Products extends Mage_Core_Model_Abstract
{
    public function deleteField($name)
    {
        $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
        $sql        = 'ALTER TABLE `' . $this->getResource()->getTable('amorderattach/order_products') . '` DROP `' . $name . '`';
        $connection->query($sql);
    }

    public function addField($type, $name)
    {
        $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
        $sql        = 'ALTER TABLE `' . $this->getResource()->getTable('amorderattach/order_products') . '` ADD `' . $name . '` ' . $this->_getSqlType($type);
        $connection->query($sql);
    }

    protected function _getSqlType($fieldType)
    {
        $type = '';
        switch ($fieldType) {
            case 'text':
            case 'file_multiple':
                $type = 'TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL';
                break;
            case 'string':
            case 'file':
                $type = 'VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL';
                break;
            case 'date':
                $type = 'DATE NULL';
                break;
            default:
                $type = 'VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL';
                break;
        }

        return $type;
    }

    public function hasField($name)
    {
        $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
        $sql        = "" . 'SELECT *
                            FROM information_schema.COLUMNS
                            WHERE
                                TABLE_SCHEMA = "' . (string)Mage::getConfig()->getNode('global/resources/default_setup/connection/dbname') . '"
                            AND TABLE_NAME = "' . $this->getResource()->getTable('amorderattach/order_products') . '"
                            AND COLUMN_NAME = "' . $name . '"
                       ';
        $res        = $connection->query($sql)->fetchAll();

        return $res ? true : false;
    }

    public function getRenderer($type = 'backend')
    {
        if ('backend' == $type) {
            $blockName = 'amorderattach/adminhtml_sales_order_view_attachment_renderer_' . strtolower($this->getType());
        } else {
            $blockName = 'amorderattach/sales_order_view_attachment_renderer_' . strtolower($this->getType());
        }
        $renderer = Mage::app()->getLayout()->createBlock($blockName, 'amorderattach.renderer.products.' . strtolower($this->getType()));
        $renderer->setAttachmentField($this->getField());

        return $renderer;
    }

    protected function _construct()
    {
        $this->_init('amorderattach/order_products');
    }
}
