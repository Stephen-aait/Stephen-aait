<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */
class Amasty_Orderattach_Block_Sales_Order_View_Attachment extends Mage_Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('amorderattach/attachment.phtml');

        $orderAttachment = Mage::getModel('amorderattach/order_field');
        $orderAttachment->load(Mage::registry('current_order')->getId(), 'order_id');
        Mage::register('current_attachment_order_field', $orderAttachment, true);
    }

    public function getAttachmentFields()
    {
        $collection = Mage::getModel('amorderattach/field')->getCollection();
        $collection->addFieldToFilter('customer_visibility', array('view', 'edit'));
        $collection->addFieldToFilter('is_enabled', 1)->setOrder('sort_order','ASC');

        return $collection;
    }

    public function getOrderAttachments()
    {
        return Mage::registry('current_attachment_order_field');
    }
}
