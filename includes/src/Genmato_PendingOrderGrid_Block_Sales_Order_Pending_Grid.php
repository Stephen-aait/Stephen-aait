<?php

/**
 * @category    Genmato
 * @package     Genmato_PendingOrderGrid
 * @copyright   Copyright (c) 2013 Genmato BV (https://genmato.com)
 */
class Genmato_PendingOrderGrid_Block_Sales_Order_Pending_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('sales_order_pending_grid');
        $this->setUseAjax(true);
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Retrieve collection class
     *
     * @return string
     */
    protected function _getCollectionClass()
    {
        return 'genmato_pendingordergrid/order_pending_collection';
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel($this->_getCollectionClass());
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {

        $fields = unserialize(Mage::getStoreConfig('genmato_pendingordergrid/configuration/fields'));

        foreach ($fields as $field) {

            if ($field['field'] == 'grid_action') {
                $actiondata = array();
                $actions = unserialize(Mage::getStoreConfig('genmato_pendingordergrid/configuration/actions'));

                foreach ($actions as $action) {
                    $data = Mage::getSingleton('genmato_pendingordergrid/actions')->getAction($action['action']);
                    if (!empty($data['acl']) && !Mage::getSingleton('admin/session')->isAllowed($data['acl'])) {
                        continue;
                    }
                    if (isset($action['title']) && !empty($action['title'])) {
                        $data['title'] = $action['title'];
                    }
                    $data['caption'] = $data['title'];
                    $data['url'] = array('base' => $data['action']);
                    $data['field'] = 'order_id';
                    $actiondata[] = $data;
                }

                if (count($actiondata) > 0) {
                    if (isset($field['width']) && !empty($field['width'])) {
                        $field['width'] .= 'px';
                    }

                    $this->addColumn(
                        'action',
                        array(
                            'header' => $field['title'],
                            'width' => $field['width'],
                            'type' => 'action',
                            'getter' => 'getId',
                            'actions' => $actiondata,
                            'filter' => false,
                            'sortable' => false,
                            'index' => 'stores',
                            'is_system' => true,
                        )
                    );
                }
            } else {
                $data = Mage::getSingleton('genmato_pendingordergrid/fields')->getField($field['field']);
                $data['header'] = $field['title'];
                $data['width'] = $field['width'];
                if (isset($data['width']) && !empty($data['width'])) {
                    $data['width'] .= 'px';
                }
                $this->addColumn($field['field'], $data);
            }
        }

        $this->addRssList('rss/order/new', Mage::helper('sales')->__('New Order RSS'));

        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $actions = unserialize(Mage::getStoreConfig('genmato_pendingordergrid/configuration/massactions'));

        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('order_ids');
        $this->getMassactionBlock()->setUseSelectAll(true);

        foreach ($actions as $id => $action) {
            $data = Mage::getSingleton('genmato_pendingordergrid/massactions')->getAction($action['action']);
            if (!$data) {
                continue;
            }
            if (!empty($data['acl']) && !Mage::getSingleton('admin/session')->isAllowed($data['acl'])) {
                continue;
            }
            if (isset($action['title']) && !empty($action['title'])) {
                $data['title'] = $action['title'];
            }
            $data['label'] = $data['title'];
            $data['url'] = $data['action'];

            $this->getMassactionBlock()->addItem($id, $data);
        }

        return $this;
    }

    public function getRowUrl($row)
    {
        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/view')) {
            return $this->getUrl('*/sales_order/view', array('order_id' => $row->getId()));
        }
        return false;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }


}