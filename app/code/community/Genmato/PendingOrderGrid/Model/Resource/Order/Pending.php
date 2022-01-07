<?php

/**
 * @category    Genmato
 * @package     Genmato_PendingOrderGrid
 * @copyright   Copyright (c) 2013 Genmato BV (https://genmato.com)
 */
class Genmato_PendingOrderGrid_Model_Resource_Order_Pending extends Mage_Core_Model_Resource_Db_Abstract
{

    protected $_useIsObjectNew = true;
    protected $_isPkAutoIncrement = false;

    protected function _construct()
    {
        $this->_init('genmato_pendingordergrid/order_pending', 'entity_id');
    }

}