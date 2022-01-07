<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */
class Amasty_Orderattach_Model_Sales_Order extends Amasty_Orderattach_Model_Sales_Order_Pure
{
    public function memo($field)
    {
        $orderAttachment = Mage::getModel('amorderattach/order_field');
        $orderAttachment->load($this->getId(), 'order_id');
        return nl2br($orderAttachment->getData($field));
    }
}