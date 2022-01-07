<?php

/**
 * @category    Genmato
 * @package     Genmato_PendingOrderGrid
 * @copyright   Copyright (c) 2013 Genmato BV (https://genmato.com)
 */
class Genmato_PendingOrderGrid_Block_Sales_Order_Pending_Column_Renderer_Items
    extends Mage_adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    public function render(Varien_Object $row)
    {

        $items = unserialize($row->getData($this->getColumn()->getIndex()));

        return $this->getLayout()->createBlock('genmato_pendingordergrid/sales_order_pending_items')->setData(
            'items',
            $items
        )->toHtml();

    }
}