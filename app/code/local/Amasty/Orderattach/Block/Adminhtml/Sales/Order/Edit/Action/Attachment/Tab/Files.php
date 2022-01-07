<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */
class Amasty_Orderattach_Block_Adminhtml_Sales_Order_Edit_Action_Attachment_Tab_Files
    extends Mage_Adminhtml_Block_Widget
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('amorderattach/order/edit/action/files.phtml');
    }

    protected function _prepareLayout()
    {
        $orderIds = Mage::registry('order_ids');

        $attachments = Mage::getModel('amorderattach/field')->getCollection()->addFieldToFilter('is_enabled', 1)
                           ->addFieldToFilter('type', array('file', 'file_multiple'));
        $this->setData('attachments', $attachments);
        $this->setData('order_ids', implode(',',$orderIds));
    }

    /**
     * ######################## TAB settings #################################
     */
    public function getTabLabel()
    {
        return Mage::helper('amorderattach')->__('Order Files');
    }

    public function getTabTitle()
    {
        return Mage::helper('amorderattach')->__('Order Files');
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }

}