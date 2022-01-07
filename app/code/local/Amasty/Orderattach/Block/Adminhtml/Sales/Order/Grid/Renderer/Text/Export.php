<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */
class Amasty_Orderattach_Block_Adminhtml_Sales_Order_Grid_Renderer_Text_Export extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
{
public function render(Varien_Object $row)
    {
        if ($data = $this->_getValue($row)) {
            return $data;
        }
        return $this->getColumn()->getDefault();
    }
}