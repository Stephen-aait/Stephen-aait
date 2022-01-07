<?php

/**
 * @category    Genmato
 * @package     Genmato_PendingOrderGrid
 * @copyright   Copyright (c) 2013 Genmato BV (https://genmato.com)
 */
class Genmato_PendingOrderGrid_Model_Order_Pending extends Mage_Core_Model_Abstract
{

    protected $_eventPrefix = 'genmato_pendingordergrid_order_pending';
    protected $_eventObject = 'order_pending';
    protected $_excludeStatus = false;

    protected function _construct()
    {
        $this->_init('genmato_pendingordergrid/order_pending');
    }

    public function loadByAttribute($attribute, $value)
    {
        $collection = $this->getCollection()->addFilter($attribute, $value);

        return $collection;
    }

    public function processOrder($order)
    {
        if (!$this->_excludeStatus) {
            $this->_excludeStatus = explode(
                ',',
                Mage::getStoreConfig('genmato_pendingordergrid/configuration/exclude_status')
            );
        }
        $this->addData($order->getData());

        Mage::dispatchEvent(
            'genmato_pendingordergrid_order_update',
            array('pending_order' => $this, 'order' => $order)
        );

        if (in_array($this->getStatus(), $this->_excludeStatus)) {
            $this->isDeleted(true);
        }

        try {
            $this->save();
        } catch (Exception $ex) {
            return false;
        }
        return true;
    }
}