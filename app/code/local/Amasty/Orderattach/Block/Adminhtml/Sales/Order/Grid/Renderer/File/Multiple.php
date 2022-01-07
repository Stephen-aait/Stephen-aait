<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */
class Amasty_Orderattach_Block_Adminhtml_Sales_Order_Grid_Renderer_File_Multiple extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $currentData = $row->getData($this->getColumn()->getIndex());
        $currentData = trim($currentData, " \t\n\r\0\x0B;");
        if ($currentData)
            $values = explode(';', $currentData);
        else
            $values = false;

        $block = $this->getLayout()
            ->createBlock('adminhtml/template')
            ->setTemplate('amorderattach/grid/file_multiple.phtml')
            ->setData('field', $this->getColumn()->getId())
            ->setData('order_id', $row->getEntityId())
            ->setData('values', $values)
        ;
        return $block->toHtml();
    }
}
