<?php

/**
 * @category    Genmato
 * @package     Genmato_PendingOrderGrid
 * @copyright   Copyright (c) 2013 Genmato BV (https://genmato.com)
 */
class Genmato_PendingOrderGrid_Model_Fields
{

    protected $_fields = false;

    public function getFields()
    {
        if (!$this->_fields) {
            $this->addFieldConfig('entity_id', array());
            $this->addFieldConfig(
                'status',
                array(
                    'type' => 'options',
                    'options' => Mage::getSingleton('sales/order_config')->getStatuses(),
                )
            );
            $this->addFieldConfig('coupon_code', array());
            $this->addFieldConfig('shipping_description', array());
            $this->addFieldConfig('is_virtual', array());
            $this->addFieldConfig(
                'store_id',
                array(
                    'type' => 'store',
                    'store_view' => true,
                    'display_deleted' => true,
                )
            );
            $this->addFieldConfig('store_name', array());
            $this->addFieldConfig('customer_id', array());
            $this->addFieldConfig(
                'discount_amount',
                array(
                    'type' => 'currency',
                    'currency' => 'order_currency_code',
                )
            );
            $this->addFieldConfig(
                'discount_canceled',
                array(
                    'type' => 'currency',
                    'currency' => 'order_currency_code',
                )
            );
            $this->addFieldConfig(
                'discount_invoiced',
                array(
                    'type' => 'currency',
                    'currency' => 'order_currency_code',
                )
            );
            $this->addFieldConfig(
                'discount_refunded',
                array(
                    'type' => 'currency',
                    'currency' => 'order_currency_code',
                )
            );
            $this->addFieldConfig(
                'grand_total',
                array(
                    'type' => 'currency',
                    'currency' => 'order_currency_code',
                )
            );
            $this->addFieldConfig(
                'shipping_amount',
                array(
                    'type' => 'currency',
                    'currency' => 'order_currency_code',
                )
            );
            $this->addFieldConfig(
                'shipping_canceled',
                array(
                    'type' => 'currency',
                    'currency' => 'order_currency_code',
                )
            );
            $this->addFieldConfig(
                'shipping_invoiced',
                array(
                    'type' => 'currency',
                    'currency' => 'order_currency_code',
                )
            );
            $this->addFieldConfig(
                'shipping_refunded',
                array(
                    'type' => 'currency',
                    'currency' => 'order_currency_code',
                )
            );
            $this->addFieldConfig(
                'shipping_tax_amount',
                array(
                    'type' => 'currency',
                    'currency' => 'order_currency_code',
                )
            );
            $this->addFieldConfig(
                'shipping_tax_refunded',
                array(
                    'type' => 'currency',
                    'currency' => 'order_currency_code',
                )
            );
            $this->addFieldConfig(
                'store_to_base_rate',
                array(
                    'type' => 'currency',
                    'currency' => 'order_currency_code',
                )
            );
            $this->addFieldConfig(
                'store_to_order_rate',
                array(
                    'type' => 'currency',
                    'currency' => 'order_currency_code',
                )
            );
            $this->addFieldConfig(
                'subtotal',
                array(
                    'type' => 'currency',
                    'currency' => 'order_currency_code',
                )
            );
            $this->addFieldConfig(
                'subtotal_canceled',
                array(
                    'type' => 'currency',
                    'currency' => 'order_currency_code',
                )
            );
            $this->addFieldConfig(
                'subtotal_invoiced',
                array(
                    'type' => 'currency',
                    'currency' => 'order_currency_code',
                )
            );
            $this->addFieldConfig(
                'subtotal_refunded',
                array(
                    'type' => 'currency',
                    'currency' => 'order_currency_code',
                )
            );
            $this->addFieldConfig(
                'tax_amount',
                array(
                    'type' => 'currency',
                    'currency' => 'order_currency_code',
                )
            );
            $this->addFieldConfig(
                'tax_canceled',
                array(
                    'type' => 'currency',
                    'currency' => 'order_currency_code',
                )
            );
            $this->addFieldConfig(
                'tax_invoiced',
                array(
                    'type' => 'currency',
                    'currency' => 'order_currency_code',
                )
            );
            $this->addFieldConfig(
                'tax_refunded',
                array(
                    'type' => 'currency',
                    'currency' => 'order_currency_code',
                )
            );
            $this->addFieldConfig(
                'total_canceled',
                array(
                    'type' => 'currency',
                    'currency' => 'order_currency_code',
                )
            );
            $this->addFieldConfig(
                'total_invoiced',
                array(
                    'type' => 'currency',
                    'currency' => 'order_currency_code',
                )
            );
            $this->addFieldConfig(
                'total_offline_refunded',
                array(
                    'type' => 'currency',
                    'currency' => 'order_currency_code',
                )
            );
            $this->addFieldConfig(
                'total_online_refunded',
                array(
                    'type' => 'currency',
                    'currency' => 'order_currency_code',
                )
            );
            $this->addFieldConfig(
                'total_paid',
                array(
                    'type' => 'currency',
                    'currency' => 'order_currency_code',
                )
            );
            $this->addFieldConfig(
                'total_due',
                array(
                    'type' => 'currency',
                    'currency' => 'order_currency_code',
                )
            );
            $this->addFieldConfig('total_qty_ordered', array());
            $this->addFieldConfig('total_refunded', array());
            $this->addFieldConfig('increment_id', array());
            $this->addFieldConfig(
                'created_at',
                array(
                    'type' => 'datetime',
                )
            );
            $this->addFieldConfig(
                'updated_at',
                array(
                    'type' => 'datetime',
                )
            );
            $this->addFieldConfig('weight', array());
            $this->addFieldConfig('customer_email', array());
            $this->addFieldConfig('customer_firstname', array());
            $this->addFieldConfig('customer_lastname', array());
            $this->addFieldConfig('customer_middlename', array());
            $this->addFieldConfig('customer_prefix', array());
            $this->addFieldConfig('customer_suffix', array());
            $this->addFieldConfig('customer_taxvat', array());
            $this->addFieldConfig(
                'customer_group_id',
                array(
                    'type' => 'options',
                    'options' => Mage::getResourceModel('customer/group_collection')->load()->toOptionHash(),
                )
            );
            $this->addFieldConfig('remote_ip', array());
            $this->addFieldConfig('x_forwarded_for', array());
            $this->addFieldConfig('shipping_method', array());
            $this->addFieldConfig('customer_note', array());

            $this->addFieldConfig('grid_action', array());

            Mage::dispatchEvent('genmato_pendingordergrid_get_fields', array('fields' => $this));
        }
        return $this->_fields;
    }

    public function getField($id)
    {
        $fields = $this->getFields();
        if (!isset($fields[$id])) {
            return;
        }
        return $fields[$id];
    }

    public function addFieldConfig($id, $data = array())
    {
        if (isset($data['renderer']) && empty($data['renderer'])) {
            unset($data['renderer']);
        }

        if (isset($data['align']) && empty($data['align'])) {
            unset($data['align']);
        }

        if (isset($data['type']) && empty($data['type'])) {
            unset($data['type']);
        }

        if (!isset($data['index']) || empty($data['index'])) {
            $data['index'] = $id;
        }

        if (isset($data['filter']) && $data['filter']) {
            unset($data['filter']);
        }

        if ((!isset($data['filter_index']) || empty($data['filter_index'])) && (!isset($data['filter']))) {
            $data['filter_index'] = $id;
        } else {
            unset($data['filter_index']);
        }

        $this->_fields[$id] = $data;
    }

    public function toOptionArray()
    {
        $fields = $this->getFields();

        $data = array();
        foreach ($fields as $id => $field) {
            $data[$id] = $field['index'];
        }
        return $data;
    }


}