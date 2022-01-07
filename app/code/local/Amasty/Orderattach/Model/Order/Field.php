<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */
class Amasty_Orderattach_Model_Order_Field extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('amorderattach/order_field');
    }
    
    public function deleteField($name)
    {
        $connection = Mage::getSingleton('core/resource') ->getConnection('core_write');
        $sql = 'ALTER TABLE `' . $this->getResource()->getTable('amorderattach/order_field') . '` DROP `' . $name . '`';
        $connection->query($sql);
    }
    
    public function addField($type, $name)
    {
        $connection = Mage::getSingleton('core/resource') ->getConnection('core_write');
        $sql = 'ALTER TABLE `' . $this->getResource()->getTable('amorderattach/order_field') . '` ADD `' . $name . '` ' . $this->_getSqlType($type);
        $connection->query($sql);
    }
    
    protected function _getSqlType($fieldType)
    {
        $type = '';
        switch ($fieldType)
        {
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
}
