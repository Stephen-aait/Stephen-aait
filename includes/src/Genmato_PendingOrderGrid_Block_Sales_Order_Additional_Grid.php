<?php

/**
 * @category    Genmato
 * @package     Genmato_PendingOrderGrid
 * @copyright   Copyright (c) 2014 Genmato BV (https://genmato.com)
 */
class Genmato_PendingOrderGrid_Block_Sales_Order_Additional_Grid
    extends Genmato_PendingOrderGrid_Block_Sales_Order_Pending_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('sales_order_additional_grid');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel($this->_getCollectionClass());
        $collection->addFieldToFilter('status', array('in' => explode(',', $this->getGrid()->getField())));
        $this->setCollection($collection);
        return Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
    }

    protected function getGrid()
    {
        return Mage::registry('genmato_pendingordergrid_additional_grid');
    }
}