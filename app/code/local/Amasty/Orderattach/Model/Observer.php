<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */
class Amasty_Orderattach_Model_Observer
{
    const SALES_ORDER_GRID_CONTROLLER = 'sales_order';

    protected $_attachments            = null;
    protected $_permissibleActions     = array('index', 'grid', 'exportCsv', 'exportExcel');
    protected $_exportActions          = array('exportCsv', 'exportExcel');
    protected $_controllerNames        = array('sales_', 'orderspro_');
    protected $_orderCollectionClasses = array(
        'Mage_Sales_Model_Resource_Order_Grid_Collection',
        'Mage_Sales_Model_Resource_Order_Collection',
        'Mage_Sales_Model_Mysql4_Order_Grid_Collection',
        'Mage_Sales_Model_Mysql4_Order_Collection'
    );
    protected $_orderViewClasses       = array('Mage_Adminhtml_Block_Sales_Order_View_Tab_Info');
    protected $_orderGridClasses       = array(
        'Mage_Adminhtml_Block_Sales_Order_Grid',
        'EM_DeleteOrder_Block_Adminhtml_Sales_Order_Grid',
        'MageWorx_Adminhtml_Block_Orderspro_Sales_Order_Grid',
        'Excellence_Salesgrid_Block_Adminhtml_Sales_Order_Grid',
        'AW_Ordertags_Block_Adminhtml_Sales_Order_Grid',
        'Raveinfosys_Deleteorder_Block_Adminhtml_Sales_Order_Grid'
    );
    protected $_orderFrontViewClasses = array(
        'Mage_Sales_Block_Order_Item_Renderer_Default'
    );

    protected $_massActionsBlockClasses = array(
        'Mage_Adminhtml_Block_Widget_Grid_Massaction',
        'Enterprise_SalesArchive_Block_Adminhtml_Sales_Order_Grid_Massaction',
    );

    public function checkoutSubmitAfter($observer)
    {
        if ($observer->getOrders()) //Multishipping
        {
            foreach ($observer->getOrders() as $order)
                $this->initOrder($order);
        } else
            $this->initOrder($observer->getOrder());
    }
    
    public function onPaypalSaveOrderAfter($observer)
    {
        if (!Mage::getSingleton('checkout/session')->getQuote()->getIsMultiShipping()) {
            $this->initOrder($observer->getOrder());
        }
    }

    public function initOrder($order)
    {
        $orderField = Mage::getModel('amorderattach/order_field')->load($order->getId(), 'order_id');

        if ($orderField->getId())
            return;

        if ($id = $order->getRelationParentId()) {
            $parent     = Mage::getModel('sales/order')->load($id);
            $orderField = Mage::getModel('amorderattach/order_field')->load($parent->getId(), 'order_id');

            if ($orderField->getId()) {
                $orderField
                    ->unsetData('entity_id')
                    ->setData('order_id', $order->getId())
                    ->save();

                return;
            }
        }

        // Set default values for order fields
        $collection = Mage::getModel('amorderattach/field')->getCollection();
        $collection->addFieldToFilter('default_value', array("neq" => ''));

        if ($collection->getSize() > 0) // Set default values
        {
            $orderField->setOrderId($order->getId());
            foreach ($collection as $field) {
                $orderField->setData($field->getCode(), $field->getDefaultValue());
            }
            $orderField->save();
        }

        // Set default values for product fields
        $collection = Mage::getModel('amorderattach/field')->getCollection();
        $collection->addFieldToFilter('default_value', array("neq" => ''));

        if ($collection->getSize() > 0) // Set default values
        {
            $orderItems = $order->getAllItems();
            foreach ($orderItems as $item) {
                $prodField = Mage::getModel('amorderattach/order_products')->load($item->getItemId(), 'order_item_id');
                foreach ($collection as $field) {
                    if ($field->getApplyToEachProduct()) {
                        $prodField->setData($field->getCode(), $field->getDefaultValue());
                    }
                }
                $prodField->setData('order_item_id', $item->getItemId());
                $prodField->save();
            }
        }
    }

    public function onModelSaveCommitAfter($observer)
    {
        $object = $observer->getObject();

        if (($object instanceof Amasty_Orderattach_Model_Field) && (Mage::registry('amorderattach_additional_data'))) {
            $data = Mage::registry('amorderattach_additional_data');
            Mage::getModel('amorderattach/order_field')->addField($data['type'], $data['code']);
            if ($data['apply_to_each_product']) {
                Mage::getModel('amorderattach/order_products')->addField($data['type'], $data['code']);
            }
            Mage::helper('amorderattach')->clearCache();
        }
    }

    public function onCoreCollectionAbstractLoadBefore($observer)
    {
        $collection = $observer->getCollection();
        if ($this->_isInstanceOf($collection, $this->_orderCollectionClasses)) {
            $this->_prepareCollection($collection);
        }
    }

    protected function _isInstanceOf($block, $classes)
    {
        $found = false;
        foreach ($classes as $className) {
            if ($block instanceof $className) {
                $found = true;
                break;
            }
        }

        return $found;
    }

    protected function _prepareCollection($collection, $place = 'order')
    {
        if ($this->_isJoined($collection->getSelect()->getPart('from')))
            return $collection;

        if (!$this->_isControllerName($place))
            return $collection;

        $attachments = $this->_getAttachments();

        if ($attachments->getSize()) {
            $isVersion14 = !Mage::helper('ambase')->isVersionLessThan(1, 4);
            $alias       = $isVersion14 ? 'main_table' : 'e';
            $fields      = array();
            foreach ($attachments as $attachment) {
                $fields[] = $attachment->getCode();
            }

            $collection->getSelect()
                       ->joinLeft(
                           array('attachments' => Mage::getModel('amorderattach/order_field')->getResource()->getTable('amorderattach/order_field')),
                           $alias . '.entity_id = attachments.order_id',
                           $fields
                       );

        }

        return $collection;
    }

    protected function _isJoined($from)
    {
        $found = false;
        foreach ($from as $alias => $data) {
            if ('attachments' === $alias) {
                $found = true;
            }
        }

        return $found;
    }

    protected function _isControllerName($place)
    {
        $found = false;
        foreach ($this->_controllerNames as $controllerName) {
            if (false !== strpos(Mage::app()->getRequest()->getControllerName(), $controllerName . $place)) {
                $found = true;
            }
        }

        return $found;
    }

    protected function _getAttachments()
    {
        if (is_null($this->_attachments)) {
            $attachments = Mage::getModel('amorderattach/field')->getCollection();
            $attachments->addFieldToFilter('show_on_grid', 1)
						->setOrder('sort_order','ASC');
						
            //echo '<pre>';print_r($attachments->getData());exit;
            
            $this->_attachments = $attachments;
        }

        return $this->_attachments;
    }

    public function handleBlockOutput($observer)
    {
        $block = $observer->getBlock();

        $transport = $observer->getTransport();
        $html      = $transport->getHtml();

        if ($this->_isInstanceOf($block, $this->_orderViewClasses) && Mage::registry('current_order')) {
            $html = $this->_prepareBackendHtml($html);
        }

        if ($this->_isInstanceOf($block, $this->_orderFrontViewClasses) && Mage::registry('current_order')) {
            $item = $block->getItem();
            $html = $this->_prepareFrontendHtml($html, $item);
        }

        $transport->setHtml($html);
    }

    protected function _prepareBackendHtml($html)
    {
        $attachments = Mage::app()->getLayout()->createBlock('amorderattach/adminhtml_sales_order_view_attachment');
        $html        = preg_replace(
            '@<div class="box-left">(\s*)<!--Billing Address-->@',
            $attachments->toHtml() . '$0',
            $html, 1
        );

        // get HTML with attachments for each products
        $orderItems  = Mage::registry('current_order')->getAllItems();
        $attachments = Mage::getModel('amorderattach/field')->getCollection();
        foreach ($orderItems as $item) {
            $itemId        = $item->getItemId();
            $search        = '@<div id="order_item_' . $itemId . '" class="item-container">(\s*)(.*?)</div>@s';
            $prod_att_data = Mage::getModel('amorderattach/order_products')->load($itemId, 'order_item_id');
            $prod_att_html = Mage::getModel('core/layout')
                                 ->createBlock('amorderattach/adminhtml_sales_order_view_attachment')
                                 ->setAttachmentFields($attachments)
                                 ->setAttProductFields($prod_att_data)
                                 ->setItemId($itemId)
                                 ->toHtml();
            $html          = preg_replace($search, '$0$1' . $prod_att_html, $html);
        }

        return $html;
    }

    protected function _prepareFrontendHtml($html, $item)
    {
        $itemId        = $item->getItemId();
        $search        = '@id="order-item-row-' . $itemId . '">(.*)<td>(.*?)</h3>@s';
        $attachments   = Mage::getModel('amorderattach/field')->getCollection();
        $prod_att_data = Mage::getModel('amorderattach/order_products')->load($itemId, 'order_item_id');
        $prod_att_html = Mage::getModel('core/layout')
            ->createBlock('amorderattach/sales_order_view_attachment')
            ->setAttachmentFields($attachments)
                             ->setAttProductFields($prod_att_data)
                             ->setItemId($itemId)
                             ->toHtml();
        $html = preg_replace($search, '$0$1' . $prod_att_html, $html);

        return $html;
    }

    public function onCoreLayoutBlockCreateAfter($observer)
    {
        $block = $observer->getBlock();
        // Order Grid
        if ($this->_isInstanceOf($block, $this->_orderGridClasses)) {
            $this->_prepareColumns($block, in_array(Mage::app()->getRequest()->getActionName(), $this->_exportActions));
        }
    }

    protected function _prepareColumns(&$grid, $export = false, $place = 'order', $after = 'grand_total')
    {
        if (!$this->_isControllerName($place) ||
            !in_array(Mage::app()->getRequest()->getActionName(), $this->_permissibleActions)
        )
            return $grid;

        $attachments = $this->_getAttachments();

        if ($attachments->getSize() > 0) {
            foreach ($attachments as $attachment) {
                $type = $attachment->getType();
                if ($export && ('file' == $type || 'file_multiple' == $type)) {
                    continue;
                }
                $column = array(
                    'header'           => Mage::helper('amorderattach')->__($attachment->getLabel()),
                    'index'            => $attachment->getCode(),
                    'filter_index'     => 'attachments.' . $attachment->getCode(),
                    'header_css_class' => 'fieldname-' . $attachment->getCode(),
                );
                switch ($type) {
                    case 'date':
                        $column['type']      = 'date';
                        $column['align']     = 'center';
                        $column['gmtoffset'] = false;
                        $column['filter_condition_callback'] = array($this, '_filterByDateWithoutTime');
                        break;
                    case 'text':
                    case 'string':
                        $column['filter']   = 'adminhtml/widget_grid_column_filter_text';
                        $column['renderer'] = 'amorderattach/adminhtml_sales_order_grid_renderer_text' . ($export ? '_export' : '');
                        $column['sortable'] = true;
                        break;
                    case 'select':
                        $selectOptions = array();
                        $options       = explode(',', $attachment->getOptions());
                        $options       = array_map('trim', $options);
                        if ($options) {
                            foreach ($options as $option) {
                                $selectOptions[$option] = $option;
                            }
                        }
                        $column['type']    = 'options';
                        $column['options'] = $selectOptions;
                        break;
                    case 'file':
                        $column['filter']   = 'adminhtml/widget_grid_column_filter_text';
                        $column['renderer'] = 'amorderattach/adminhtml_sales_order_grid_renderer_file';
                        $column['sortable'] = true;
                        break;
                    case 'file_multiple':
                        $column['filter']   = 'adminhtml/widget_grid_column_filter_text';
                        $column['renderer'] = 'amorderattach/adminhtml_sales_order_grid_renderer_file_multiple';
                        $column['sortable'] = false;
                        break;
                }
                $grid->addColumnAfter($column['index'], $column, $after);
                $after = $column['index'];
            }
        }

        return $grid;
    }

    /**
     * @param Varien_Event_Observer $observer
     * @throws Exception
     * @return null
     */
    public function coreBlockAbstractPrepareLayoutBefore(Varien_Event_Observer $observer)
    {
        $block =$observer->getEvent()->getBlock();

        if ($this->_isInstanceOf($block, $this->_massActionsBlockClasses) === true) {
            return $this->_addMassActions($block);
        }
    }

    /**
     * @param Mage_Adminhtml_Block_Page $block
     * @throws Exception
     * @return void
     */
    protected function _addMassActions($block)
    {
        switch($block->getRequest()->getControllerName()) {
            case self::SALES_ORDER_GRID_CONTROLLER:
                if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/mass_edit_order_attach')) {
                    $block->addItem('update_order_attach', array(
                        'label'=> Mage::helper('sales')->__('Edit Orders Notes and Files'),
                        'url'  => Mage::app()->getStore()->getUrl('adminhtml/amorderattach_orderAttach/edit')
                    ));
                }
                break;
        }
    }

    /**
     * @param Mage_Sales_Model_Resource_Order_Grid_Collection $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     */
    public function _filterByDateWithoutTime($collection, $column)
    {
        $filterValue = $column->getFilter()->getValue();

        if (!is_null($filterValue)) {
            $where = '';
            $columnId = 'attachments.' . $column->getId();

            if (isset($filterValue['from'])) {
                /** @var DateTime $dateFrom */
                $dateFrom = $this->getDate($filterValue['from']);
                $where = $where . $columnId . " >= '" . $dateFrom->format('Y-m-d') . "'";
            }

            if (isset($filterValue['to'])) {
                /** @var DateTime $dateTo */
                $dateTo = $this->getDate($filterValue['to']);
                if (isset($dateFrom)) {
                    $where = $where . ' AND ';
                }
                $where = $where . $columnId . " <= '" . $dateTo->format('Y-m-d') . "'";
            }

            $collection->getSelect()->where($where);
        }

    }

    /**
     * @param Zend_Date $zendDate
     *
     * @return DateTime
     */
    protected function getDate(Zend_Date $zendDate)
    {
        $dateTimeStamp = $zendDate->getTimestamp();
        return new DateTime('@'. $dateTimeStamp);

    }
}
