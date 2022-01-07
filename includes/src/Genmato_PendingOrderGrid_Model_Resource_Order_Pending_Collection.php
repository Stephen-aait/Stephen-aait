<?php

/**
 * @category    Genmato
 * @package     Genmato_PendingOrderGrid
 * @copyright   Copyright (c) 2013 Genmato BV (https://genmato.com)
 */
class Genmato_PendingOrderGrid_Model_Resource_Order_Pending_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{

    protected $_eventPrefix = 'genmato_pendingordergrid_order_pending_collection';
    protected $_eventObject = 'genmato_pendingordergrid_order_pending_collection';

    protected function _construct()
    {
        $this->_init('genmato_pendingordergrid/order_pending');
    }

    /**
     * Emulate simple add attribute filter to collection
     *
     * @param string $attribute
     * @param mixed  $condition
     *
     * @return Mage_Catalog_Model_Resource_Category_Flat_Collection
     */
    public function addAttributeToFilter($attribute, $condition = null)
    {
        if (!is_string($attribute) || $condition === null) {
            return $this;
        }

        return $this->addFieldToFilter($attribute, $condition);
    }
}