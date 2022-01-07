<?php

/**
 * @category    Genmato
 * @package     Genmato_PendingOrderGrid
 * @copyright   Copyright (c) 2013 Genmato BV (https://genmato.com)
 */
class Genmato_PendingOrderGrid_Block_Sales_Order_Pending_Items extends Mage_Core_Block_Template
{

    protected function _construct()
    {
        $this->setTemplate('genmato/pendingordergrid/items.phtml');
        $this->setShowProfiler(true);
    }
}