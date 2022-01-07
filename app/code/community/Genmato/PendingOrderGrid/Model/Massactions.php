<?php

/**
 * @category    Genmato
 * @package     Genmato_PendingOrderGrid
 * @copyright   Copyright (c) 2013 Genmato BV (https://genmato.com)
 */
class Genmato_PendingOrderGrid_Model_Massactions
{

    protected $_massactions = false;

    public function getMassActions()
    {
        if (!$this->_massactions) {
            $this->addMassAction(
                'sales_order_cancel',
                array(
                    'title' => 'Cancel',
                    'action' => Mage::helper('adminhtml')->getUrl('adminhtml/sales_order/massCancel'),
                    'acl' => 'sales/order/actions/cancel',
                )
            );
            $this->addMassAction(
                'sales_order_hold',
                array(
                    'title' => 'Hold',
                    'action' => Mage::helper('adminhtml')->getUrl('adminhtml/sales_order/massHold'),
                    'acl' => 'sales/order/actions/cancel',
                )
            );
            $this->addMassAction(
                'sales_order_unhold',
                array(
                    'title' => 'Unhold',
                    'action' => Mage::helper('adminhtml')->getUrl('adminhtml/sales_order/massUnhold'),
                    'acl' => 'sales/order/actions/unhold',
                )
            );
            $this->addMassAction(
                'sales_order_print_invoice',
                array(
                    'title' => 'Print Invoices',
                    'action' => Mage::helper('adminhtml')->getUrl('adminhtml/sales_order/pdfinvoices'),
                )
            );
            $this->addMassAction(
                'sales_order_print_shipment',
                array(
                    'title' => 'Print Shipment',
                    'action' => Mage::helper('adminhtml')->getUrl('adminhtml/sales_order/pdfshipments'),
                )
            );
            $this->addMassAction(
                'sales_order_print_creditmemo',
                array(
                    'title' => 'Print Creditmemo',
                    'action' => Mage::helper('adminhtml')->getUrl('adminhtml/sales_order/pdfcreditmemos'),
                )
            );
            $this->addMassAction(
                'sales_order_print_docs',
                array(
                    'title' => 'Print Docs',
                    'action' => Mage::helper('adminhtml')->getUrl('adminhtml/sales_order/pdfdocs'),
                )
            );
            $this->addMassAction(
                'sales_order_print_ship_labels',
                array(
                    'title' => 'Print Shipping Labels',
                    'action' => Mage::helper('adminhtml')->getUrl(
                        'adminhtml/sales_order_shipment/massPrintShippingLabel'
                    ),
                )
            );

            Mage::dispatchEvent('genmato_pendingordergrid_get_massactions', array('massactions' => $this));
        }
        return $this->_massactions;
    }

    public function getAction($id)
    {
        $massactions = $this->getMassActions();
        if (!isset($massactions[$id])) {
            return false;
        }
        return $massactions[$id];
    }

    public function addMassAction($id, $data = array())
    {

        $this->_massactions[$id] = $data;
    }

    public function toOptionArray()
    {
        $massactions = $this->getMassActions();

        $data = array();
        foreach ($massactions as $id => $massaction) {
            $data[$id] = $massaction['title'];
        }
        return $data;
    }


}