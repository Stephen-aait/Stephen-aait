<?php

/**
 * @category    Genmato
 * @package     Genmato_PendingOrderGrid
 * @copyright   Copyright (c) 2014 Genmato BV (https://genmato.com)
 */
class Genmato_PendingOrderGrid_Sales_Order_AdditionalController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Init layout, menu and breadcrumb
     *
     * @return Mage_Adminhtml_Sales_OrderController
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('sales/order_addtional')
            ->_addBreadcrumb($this->__('Sales'), $this->__('Sales'))
            ->_addBreadcrumb($this->__('Pending Orders'), $this->__('Pending Orders'))
            ->_addBreadcrumb($this->__('Additional Grid'), $this->__('Additional Grid'));
        return $this;
    }

    public function indexAction()
    {
        $this->_title($this->__('Sales'))->_title($this->__('Pending Orders'))->_title($this->__('Additional'));
        $additional = unserialize(Mage::getStoreConfig('genmato_pendingordergrid/additional/grids'));
        $id = $this->getRequest()->getParam('grid', false);

        if (!$id || !isset($additional[$id])) {
            $this->_initAction()->_addContent(
                $this->getLayout()->createBlock('genmato_pendingordergrid/sales_order_pending')
            )
                ->renderLayout();
        } else {
            Mage::register('genmato_pendingordergrid_additional_grid', new Varien_Object($additional[$id]));
            $this->_initAction()->_addContent(
                $this->getLayout()->createBlock('genmato_pendingordergrid/sales_order_additional')
            )
                ->renderLayout();
        }

    }

    public function gridAction()
    {
        $this->_title($this->__('Sales'))->_title($this->__('Pending Orders'))->_title($this->__('Additional'));
        $additional = unserialize(Mage::getStoreConfig('genmato_pendingordergrid/additional/grids'));
        $id = $this->getRequest()->getParam('grid', false);

        if (!$id || !isset($additional[$id])) {
            $this->_initAction();
            $this->getResponse()->setBody(
                $this->getLayout()->createBlock('genmato_pendingordergrid/sales_order_additional_grid')
                    ->toHtml()
            );
        } else {
            Mage::register('genmato_pendingordergrid_additional_grid', new Varien_Object($additional[$id]));
            $this->_initAction();
            $this->getResponse()->setBody(
                $this->getLayout()->createBlock('genmato_pendingordergrid/sales_order_additional_grid')
                    ->toHtml()
            );
        }
    }

    /**
     * Export order grid to CSV format
     */
    public function exportCsvAction()
    {
        $additional = unserialize(Mage::getStoreConfig('genmato_pendingordergrid/additional/grids'));
        $id = $this->getRequest()->getParam('grid', false);
        if ($id && isset($additional[$id])) {
            Mage::register('genmato_pendingordergrid_additional_grid', new Varien_Object($additional[$id]));
            $grid       = $this->getLayout()->createBlock('genmato_pendingordergrid/sales_order_additional_grid');
        } else {
            $grid       = $this->getLayout()->createBlock('genmato_pendingordergrid/sales_order_pending_grid');
        }

        $fileName   = 'pendingorders.csv';
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    /**
     *  Export order grid to Excel XML format
     */
    public function exportExcelAction()
    {
        $additional = unserialize(Mage::getStoreConfig('genmato_pendingordergrid/additional/grids'));
        $id = $this->getRequest()->getParam('grid', false);
        if ($id && isset($additional[$id])) {
            Mage::register('genmato_pendingordergrid_additional_grid', new Varien_Object($additional[$id]));
            $grid       = $this->getLayout()->createBlock('genmato_pendingordergrid/sales_order_additional_grid');
        } else {
            $grid       = $this->getLayout()->createBlock('genmato_pendingordergrid/sales_order_pending_grid');
        }

        $fileName   = 'pendingorders.xml';
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('sales/genmato_pendingordergrid');
    }
}