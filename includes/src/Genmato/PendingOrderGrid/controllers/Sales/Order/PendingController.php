<?php

/**
 * @category    Genmato
 * @package     Genmato_PendingOrderGrid
 * @copyright   Copyright (c) 2013 Genmato BV (https://genmato.com)
 */
class Genmato_PendingOrderGrid_Sales_Order_PendingController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Init layout, menu and breadcrumb
     *
     * @return Mage_Adminhtml_Sales_OrderController
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('sales/order_pending')
            ->_addBreadcrumb($this->__('Sales'), $this->__('Sales'))
            ->_addBreadcrumb($this->__('Pending Orders'), $this->__('Pending Orders'));
        return $this;
    }

    public function indexAction()
    {
        $this->_title($this->__('Sales'))->_title($this->__('Pending Orders'));

        $this->_initAction()->_addContent(
            $this->getLayout()->createBlock('genmato_pendingordergrid/sales_order_pending')
        )
            ->renderLayout();
    }

    public function gridAction()
    {
        $this->_initAction();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('genmato_pendingordergrid/sales_order_pending_grid')
                ->toHtml()
        );
    }

    /**
     * Export order grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName   = 'pendingorders.csv';
        $grid       = $this->getLayout()->createBlock('genmato_pendingordergrid/sales_order_pending_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    /**
     *  Export order grid to Excel XML format
     */
    public function exportExcelAction()
    {
        $fileName   = 'pendingorders.xml';
        $grid       = $this->getLayout()->createBlock('genmato_pendingordergrid/sales_order_pending_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }

    public function reloadAction()
    {
        $excludeStatus = explode(',', Mage::getStoreConfig('genmato_pendingordergrid/configuration/exclude_status'));


        $collection = Mage::getResourceModel('sales/order_collection')
            ->addFieldToFilter('status', array('nin' => $excludeStatus));

        Mage::getSingleton('core/resource_iterator')
            ->walk($collection->getSelect(), array(array($this, 'reloadCallback')));

        $collection = Mage::getResourceModel('genmato_pendingordergrid/order_pending_collection')
            ->addFieldToFilter('status', array('in' => $excludeStatus));

        foreach ($collection as $item) {
            $item->delete();
        }

        //$errors = array();
        //foreach ($collection as $item) {
        //    if (!$pendingOrder->processOrder($item)) {
        //        $errors[] = $item->getIncrementId();
        //    }
        //}
        //if (count($errors) > 0) {
        //    Mage::getSingleton('adminhtml/session')->addError(
        //        $this->__('There was an error processing order(s): %s', implode(', ', $errors))
        //    );
        //} else {
        Mage::getSingleton('adminhtml/session')->addSuccess($this->__('All pending orders are refreshed'));
        //}


        $this->_redirectReferer();
    }

    public function reloadCallback($args)
    {
        $item = Mage::getModel('sales/order');
        $item->setData($args['row']);

        $pendingOrder = Mage::getSingleton('genmato_pendingordergrid/order_pending');
        $pendingOrder->processOrder($item);
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('sales/genmato_pendingordergrid');
    }
}