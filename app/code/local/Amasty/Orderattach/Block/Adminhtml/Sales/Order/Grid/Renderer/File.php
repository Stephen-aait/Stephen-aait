<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */
class Amasty_Orderattach_Block_Adminhtml_Sales_Order_Grid_Renderer_File extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $block = $this->getLayout()
            ->createBlock('adminhtml/template')
            ->setTemplate('amorderattach/grid/file.phtml')
            ->setData('value', $row->getData($this->getColumn()->getIndex()))
            ->setData('field', $this->getColumn()->getId())
            ->setData('order_id', $row->getEntityId())
        ;
        return $block->toHtml();
    }
}