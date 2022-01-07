<?php

/**
 * @category    Genmato
 * @package     Genmato_PendingOrderGrid
 * @copyright   Copyright (c) 2013 Genmato BV (https://genmato.com)
 */
class Genmato_PendingOrderGrid_Model_Observer
{

    public function salesOrderSaveCommitAfter(Varien_Event_Observer $observer)
    {
        $order = Mage::getModel('sales/order')->load($observer->getEvent()->getOrder()->getId());

        Mage::getModel('genmato_pendingordergrid/order_pending')->processOrder($order);
    }

    public function genmatoPendingordergridOrderUpdate(Varien_Event_Observer $observer)
    {
        $productVisibility = Mage::getStoreConfig('genmato_pendingordergrid/configuration/sku_visibility');

        $pending = $observer->getEvent()->getPendingOrder();
        $order = $observer->getEvent()->getOrder();

        if (!$order->getId()) {
            return;
        }

        try {
            $billingFormatted = '';
            if ($order->getBillingAddress()) {
                $billingFormatted = $order->getBillingAddress()->getFormated('text');
            }

            // Add formatted Billing Address
            $pending->setData('billing_address_formatted', $billingFormatted);

            // Add Billing Name
            $pending->setData('billing_name', $this->formatName($order->getBillingAddress()));

            // Add formatted Shipping Address
            if (!$order->getIsVirtual()) {
                $pending->setData('shipping_address_formatted', $order->getShippingAddress()->getFormated('text'));

                // Add Shipping Name
                $pending->setData('shipping_name', $this->formatName($order->getShippingAddress()));
            }

            // Add Payment Method Code
            $pending->setData('payment_method_code', $order->getPayment()->getMethod());

            // Add Payment Method Title
            $pending->setData('payment_method', $order->getPayment()->getMethodInstance()->getTitle());

            // Add ordered items
            $items = array();
            foreach ($order->getAllItems() as $item) {
                $tmp = array();
                if ($item->getHasChildren(
                    ) && ($productVisibility == Genmato_PendingOrderGrid_Model_System_Config_Source_Product_Visibility::PRODUCT_HIDE_PARENT)
                ) {
                    // Hide parent product line from item grid
                    continue;
                }
                // Load Item details
                $tmp['item_id'] = $item->getId();
                $tmp['parent_id'] = $item->getParentId();
                $tmp['name'] = $item->getName();
                $tmp['product_id'] = $item->getProductId();
                $tmp['sku'] = $item->getSku();

                //
                if ($item->getParentItemId(
                    ) && ($productVisibility == Genmato_PendingOrderGrid_Model_System_Config_Source_Product_Visibility::PRODUCT_HIDE_PARENT)
                ) {
                    // Use ordered Qty from parent item
                    $item = Mage::getModel('sales/order_item')->load($item->getParentItemId());
                } elseif ($item->getParentItemId(
                    ) && ($productVisibility == Genmato_PendingOrderGrid_Model_System_Config_Source_Product_Visibility::PRODUCT_IDENT_CHILD)
                ) {
                    $tmp['sku'] = '<span class=\'ident\'>' . $item->getSku() . '<span>';
                }
                if ($item->getIsQtyDecimal()) {
                    $tmp['qty_backordered'] = number_format($item->getQtyBackordered(), 2);
                    $tmp['qty_canceled'] = number_format($item->getQtyCanceled(), 2);
                    $tmp['qty_invoiced'] = number_format($item->getQtyInvoiced(), 2);
                    $tmp['qty_ordered'] = number_format($item->getQtyOrdered(), 2);
                    $tmp['qty_refunded'] = number_format($item->getQtyRefunded(), 2);
                    $tmp['qty_shipped'] = number_format($item->getQtyShipped(), 2);
                    $tmp['qty_toship'] = number_format($item->getQtyToship(), 2);
                } else {
                    $tmp['qty_backordered'] = number_format($item->getQtyBackordered(), 0);
                    $tmp['qty_canceled'] = number_format($item->getQtyCanceled(), 0);
                    $tmp['qty_invoiced'] = number_format($item->getQtyInvoiced(), 0);
                    $tmp['qty_ordered'] = number_format($item->getQtyOrdered(), 0);
                    $tmp['qty_refunded'] = number_format($item->getQtyRefunded(), 0);
                    $tmp['qty_shipped'] = number_format($item->getQtyShipped(), 0);
                    $tmp['qty_toship'] = number_format($item->getQtyToship(), 0);
                }
                $tmp['class'] = '';
                if ($item->getQtyToShip() > 0) {
                    $tmp['class'] = 'readytoship';
                }
                if ($item->getQtyBackordered() > 0) {
                    $tmp['class'] = 'backorderqty';
                }

                $items[] = $tmp;
            }

            $pending->setData('ordered_items', serialize($items));
        } catch (Execption $ex) {
            return $this;
        }
        return $this;
    }

    public function genmatoPendingordergridGetFields(Varien_Event_Observer $observer)
    {
        $fields = $observer->getEvent()->getFields();

        $fields->addFieldConfig(
            'ordered_items',
            array(
                'renderer' => 'Genmato_PendingOrderGrid_Block_Sales_Order_Pending_Column_Renderer_Items',
            )
        );
        $fields->addFieldConfig(
            'shipping_address_formatted',
            array('renderer' => 'Genmato_PendingOrderGrid_Block_Sales_Order_Pending_Column_Renderer_Address')
        );
        $fields->addFieldConfig(
            'billing_address_formatted',
            array('renderer' => 'Genmato_PendingOrderGrid_Block_Sales_Order_Pending_Column_Renderer_Address')
        );
        $fields->addFieldConfig('payment_method', array());
        $fields->addFieldConfig('payment_method_code', array());
        $fields->addFieldConfig('shipping_name', array());
        $fields->addFieldConfig('billing_name', array());
    }

    protected function formatName($address)
    {
        $name = array();
        if ($address->hasPrefix()) {
            $name[] = $address->getPrefix();
        }
        if ($address->hasFirstname()) {
            $name[] = $address->getFirstname();
        }
        if ($address->hasMiddlename()) {
            $name[] = $address->getMiddlename();
        }
        if ($address->hasLastname()) {
            $name[] = $address->getLastname();
        }
        if ($address->hasSuffix()) {
            $name[] = $address->getSuffix();
        }
        return implode(' ', $name);
    }

    public function adminhtmlBlockHtmlBefore(Varien_Event_Observer $observer)
    {
        if (!($observer->getBlock() instanceof Mage_Adminhtml_Block_Page_Menu)) {
            return;
        }
        $additional = unserialize(Mage::getStoreConfig('genmato_pendingordergrid/additional/grids'));
        if (!is_array($additional) || count($additional) == 0) {
            return;
        }
        $config = Mage::getSingleton('admin/config')->getAdminhtmlConfig();
        $menu = $config->getNode('menu');

        /* @var $target Varien_Simplexml_Element */
        $target = $menu->sales->children->genmato_pendingordergrid->addChild('children');
        $template = $config->getNode('menutemplate/genmato_pendingordergrid/_code_');
        $templateXml = $template->asXml();

        $sortorder = 0;
        foreach ($additional as $code => $data) {
            $child = simplexml_load_string(
                str_replace(
                    array('_code_', '_title_', '_sortorder_'),
                    array($code, $data['title'], $sortorder++),
                    $templateXml
                )
            );
            $target->appendChild($child);
        }
    }
}