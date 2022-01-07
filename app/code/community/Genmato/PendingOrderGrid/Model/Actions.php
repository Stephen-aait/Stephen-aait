<?php

/**
 * @category    Genmato
 * @package     Genmato_PendingOrderGrid
 * @copyright   Copyright (c) 2013 Genmato BV (https://genmato.com)
 */
class Genmato_PendingOrderGrid_Model_Actions
{

    protected $_actions = false;

    public function getActions()
    {
        if (!$this->_actions) {
            $this->addAction(
                'sales_order_ship',
                array(
                    'title' => 'Ship',
                    'action' => 'adminhtml/sales_order_shipment/new',
                    'acl' => 'sales/order/actions/ship',
                )
            );
            $this->addAction(
                'sales_order_invoice',
                array(
                    'title' => 'Invoice',
                    'action' => 'adminhtml/sales_order_invoice/new',
                    'acl' => 'sales/order/actions/invoice',
                )
            );
            $this->addAction(
                'sales_order_view',
                array(
                    'title' => 'View',
                    'action' => 'adminhtml/sales_order/view',
                    'acl' => 'sales/order/actions/view',
                )
            );
            $this->addAction(
                'sales_order_hold',
                array(
                    'title' => 'Hold',
                    'action' => 'adminhtml/sales_order/hold',
                    'acl' => 'sales/order/actions/hold',
                )
            );
            $this->addAction(
                'sales_order_unhold',
                array(
                    'title' => 'Unhold',
                    'action' => 'adminhtml/sales_order/unhold',
                    'acl' => 'sales/order/actions/unhold',
                )
            );
            $this->addAction(
                'sales_order_cancel',
                array(
                    'title' => 'Cancel',
                    'action' => 'adminhtml/sales_order/cancel',
                    'acl' => 'sales/order/actions/cancel',
                )
            );
            $this->addAction(
                'sales_order_creditmemo',
                array(
                    'title' => 'Creditmemo',
                    'action' => 'adminhtml/sales_order_creditmemo/new',
                    'acl' => 'sales/order/actions/creditmemo',
                )
            );

            Mage::dispatchEvent('genmato_pendingordergrid_get_actions', array('actions' => $this));
        }
        return $this->_actions;
    }

    public function getAction($id)
    {
        $actions = $this->getActions();
        if (!isset($actions[$id])) {
            return;
        }
        return $actions[$id];
    }

    public function addAction($id, $data = array())
    {
        if (!isset($data['acl'])) {
            $data['acl'] = '';
        }
        $this->_actions[$id] = $data;
    }

    public function toOptionArray()
    {
        $actions = $this->getActions();

        $data = array();
        foreach ($actions as $id => $action) {
            $data[$id] = $action['title'];
        }
        return $data;
    }


}