<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */
class Amasty_Orderattach_Block_Adminhtml_Sales_Order_View_Tab_Info extends Mage_Adminhtml_Block_Sales_Order_View_Tab_Info
{
    protected function _toHtml()
    {
        $html        = parent::_toHtml();
        $attachments = Mage::app()->getLayout()->createBlock('amorderattach/adminhtml_sales_order_view_attachment');
        $html = preg_replace('@<div class="box-left">(\s*)<!--Billing Address-->(\s*)<div class="entry-edit">(\s*)<div class="entry-edit-head">(\s*)(.*?)head-billing-address@', 
                                    $attachments->toHtml() .'<div class="box-left"><div class="entry-edit"><div class="entry-edit-head">$5head-billing-address', $html, 1);
        return $html;
    }
}