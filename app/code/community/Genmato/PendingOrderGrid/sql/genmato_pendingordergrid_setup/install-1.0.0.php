<?php
/**
 * @category    Genmato
 * @package     Genmato_PendingOrderGrid
 * @copyright   Copyright (c) 2013 Genmato BV (https://genmato.com)
 */


$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('genmato_pendingordergrid/order_pending'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
        ),
        'Entity Id'
    )
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(), 'Status')
    ->addColumn('coupon_code', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Coupon Code')
    ->addColumn('shipping_description', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Shipping Description')
    ->addColumn(
        'is_virtual',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'unsigned' => true,
        ),
        'Is Virtual'
    )
    ->addColumn(
        'store_id',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'unsigned' => true,
        ),
        'Store Id'
    )
    ->addColumn('store_name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Store Name')
    ->addColumn(
        'customer_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned' => true,
        ),
        'Customer Id'
    )
    ->addColumn('discount_amount', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(), 'Discount Amount')
    ->addColumn('discount_canceled', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(), 'Discount Canceled')
    ->addColumn('discount_invoiced', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(), 'Discount Invoiced')
    ->addColumn('discount_refunded', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(), 'Discount Refunded')
    ->addColumn('grand_total', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(), 'Grand Total')
    ->addColumn('shipping_amount', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(), 'Shipping Amount')
    ->addColumn('shipping_canceled', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(), 'Shipping Canceled')
    ->addColumn('shipping_invoiced', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(), 'Shipping Invoiced')
    ->addColumn('shipping_refunded', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(), 'Shipping Refunded')
    ->addColumn('shipping_tax_amount', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(), 'Shipping Tax Amount')
    ->addColumn('shipping_tax_refunded', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(), 'Shipping Tax Refunded')
    ->addColumn('store_to_base_rate', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(), 'Store To Base Rate')
    ->addColumn('store_to_order_rate', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(), 'Store To Order Rate')
    ->addColumn('subtotal', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(), 'Subtotal')
    ->addColumn('subtotal_canceled', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(), 'Subtotal Canceled')
    ->addColumn('subtotal_invoiced', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(), 'Subtotal Invoiced')
    ->addColumn('subtotal_refunded', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(), 'Subtotal Refunded')
    ->addColumn('tax_amount', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(), 'Tax Amount')
    ->addColumn('tax_canceled', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(), 'Tax Canceled')
    ->addColumn('tax_invoiced', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(), 'Tax Invoiced')
    ->addColumn('tax_refunded', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(), 'Tax Refunded')
    ->addColumn('total_canceled', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(), 'Total Canceled')
    ->addColumn('total_invoiced', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(), 'Total Invoiced')
    ->addColumn('total_offline_refunded', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(), 'Total Offline Refunded')
    ->addColumn('total_online_refunded', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(), 'Total Online Refunded')
    ->addColumn('total_paid', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(), 'Total Paid')
    ->addColumn('total_due', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(), 'Total Due')
    ->addColumn('total_qty_ordered', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(), 'Total Qty Ordered')
    ->addColumn('total_refunded', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(), 'Total Refunded')
    ->addColumn('increment_id', Varien_Db_Ddl_Table::TYPE_TEXT, 50, array(), 'Increment Id')
    ->addColumn('order_currency_code', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Order Currency Code')
    ->addColumn('shipping_name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Shipping Name')
    ->addColumn('billing_name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Billing Name')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(), 'Created At')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(), 'Updated At')
    ->addColumn(
        'customer_is_guest',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'unsigned' => true,
        ),
        'Customer Is Guest'
    )
    ->addColumn('customer_group_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(), 'Customer Group Id')
    ->addColumn('weight', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(), 'Weight')
    ->addColumn('customer_email', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Customer Email')
    ->addColumn('customer_firstname', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Customer Firstname')
    ->addColumn('customer_lastname', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Customer Lastname')
    ->addColumn('customer_middlename', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Customer Middlename')
    ->addColumn('customer_prefix', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Customer Prefix')
    ->addColumn('customer_suffix', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Customer Suffix')
    ->addColumn('customer_taxvat', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Customer Taxvat')
    ->addColumn('customer_group_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(), 'Customer Group Id')
    ->addColumn('remote_ip', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Remote Ip')
    ->addColumn('x_forwarded_for', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'X Forwarded For')
    ->addColumn('shipping_method', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Shipping Method')
    ->addColumn('customer_note', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(), 'Customer Note')
    ->addColumn('ordered_items', Varien_Db_Ddl_Table::TYPE_TEXT, '8k', array(), 'Ordered items')
    ->addColumn(
        'shipping_address_formatted',
        Varien_Db_Ddl_Table::TYPE_TEXT,
        '1k',
        array(),
        'Formatted Shipping Address'
    )
    ->addColumn('billing_address_formatted', Varien_Db_Ddl_Table::TYPE_TEXT, '1k', array(), 'Formatted Billing Address')
    ->addColumn('payment_method_code', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Payment Method Code')
    ->addColumn('payment_method', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Payment Method')
    ->addIndex(
        $installer->getIdxName('genmato_pendingordergrid/order_pending', array('status')),
        array('status')
    )
    ->addIndex(
        $installer->getIdxName('genmato_pendingordergrid/order_pending', array('store_id')),
        array('store_id')
    )
    ->addIndex(
        $installer->getIdxName('genmato_pendingordergrid/order_pending', array('grand_total')),
        array('grand_total')
    )
    ->addIndex(
        $installer->getIdxName('genmato_pendingordergrid/order_pending', array('total_paid')),
        array('total_paid')
    )
    ->addIndex(
        $installer->getIdxName(
            'genmato_pendingordergrid/order_pending',
            array('increment_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('increment_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    )
    ->addIndex(
        $installer->getIdxName('genmato_pendingordergrid/order_pending', array('shipping_name')),
        array('shipping_name')
    )
    ->addIndex(
        $installer->getIdxName('genmato_pendingordergrid/order_pending', array('billing_name')),
        array('billing_name')
    )
    ->addIndex(
        $installer->getIdxName('genmato_pendingordergrid/order_pending', array('created_at')),
        array('created_at')
    )
    ->addIndex(
        $installer->getIdxName('genmato_pendingordergrid/order_pending', array('customer_id')),
        array('customer_id')
    )
    ->addIndex(
        $installer->getIdxName('genmato_pendingordergrid/order_pending', array('updated_at')),
        array('updated_at')
    )
    ->addForeignKey(
        $installer->getFkName('genmato_pendingordergrid/order_pending', 'customer_id', 'customer/entity', 'entity_id'),
        'customer_id',
        $installer->getTable('customer/entity'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_SET_NULL,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $installer->getFkName('genmato_pendingordergrid/order_pending', 'entity_id', 'sales/order', 'entity_id'),
        'entity_id',
        $installer->getTable('sales/order'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $installer->getFkName('genmato_pendingordergrid/order_pending', 'store_id', 'core/store', 'store_id'),
        'store_id',
        $installer->getTable('core/store'),
        'store_id',
        Varien_Db_Ddl_Table::ACTION_SET_NULL,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Sales Order Pending');
$installer->getConnection()->createTable($table);

$installer->endSetup();