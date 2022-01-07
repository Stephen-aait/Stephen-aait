<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */
class Amasty_Orderattach_Block_Adminhtml_Sales_Order_Grid extends Mage_Adminhtml_Block_Sales_Order_Grid
{
    protected function _prepareCollection()
    {
        if (method_exists($this, '_getCollectionClass'))
        {
            $collection = Mage::getResourceModel($this->_getCollectionClass());
            /* @var $collection Mage_Sales_Model_Mysql4_Order_Grid_Collection */
        } else 
        {
            // for Magento 1.3 - 1.4.0.x
            $collection = Mage::getResourceModel('sales/order_collection')
                                ->addAttributeToSelect('*')
                                ->joinAttribute('billing_firstname', 'order_address/firstname', 'billing_address_id', null, 'left')
                                ->joinAttribute('billing_lastname', 'order_address/lastname', 'billing_address_id', null, 'left')
                                ->joinAttribute('shipping_firstname', 'order_address/firstname', 'shipping_address_id', null, 'left')
                                ->joinAttribute('shipping_lastname', 'order_address/lastname', 'shipping_address_id', null, 'left')
                                ->addExpressionAttributeToSelect('billing_name',
                                    'CONCAT({{billing_firstname}}, " ", {{billing_lastname}})',
                                    array('billing_firstname', 'billing_lastname'))
                                ->addExpressionAttributeToSelect('shipping_name',
                                    'CONCAT({{shipping_firstname}},  IFNULL(CONCAT(\' \', {{shipping_lastname}}), \'\'))',
                                    array('shipping_firstname', 'shipping_lastname'));
        }
        
        $attachments = Mage::getModel('amorderattach/field')->getCollection();
        $attachments->addFieldToFilter('show_on_grid', 1);
        $attachments->load();
        
        if ($attachments->getSize())
        {
            $fields = array();
            foreach ($attachments as $attachment)
            {
                $fields[] = $attachment->getFieldname();
            }

            $collection->getSelect()
                       ->joinLeft(
                            array('attachments' => Mage::getModel('amorderattach/order_field')->getResource()->getTable('amorderattach/order_field')),
                            "main_table.entity_id = attachments.order_id",
                            $fields
                       );
            
        }
        $this->setCollection($collection);
        return Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
    }
    
    protected function _prepareColumns()
    {
        parent::_prepareColumns();
        
        $actionsColumn = null;
        if (isset($this->_columns['action']))
        {
            $actionsColumn = $this->_columns['action'];
            unset($this->_columns['action']);
        }
        
        $attachments = Mage::getModel('amorderattach/field')->getCollection();
        $attachments->addFieldToFilter('show_on_grid', 1);
        $attachments->load();
        if ($attachments->getSize())
        {
            foreach ($attachments as $attachment)
            {
                switch ($attachment->getType())
                {
                    case 'date':
                        $this->addColumn($attachment->getFieldname(), array(
                            'header'       => $this->__($attachment->getLabel()),
                            'type'         => 'date',
                            'align'        => 'center',
                            'index'        => $attachment->getFieldname(),
                            'filter_index' => 'attachments.'.$attachment->getFieldname(),
                            'gmtoffset'    => false,
                        ));
                        break;
                    case 'text':
                    case 'string':
                        $this->addColumn($attachment->getFieldname(), array(
                            'header'       => $this->__($attachment->getLabel()),
                            'index'        => $attachment->getFieldname(),
                            'filter_index' => 'attachments.'.$attachment->getFieldname(),
                            'filter'       => 'adminhtml/widget_grid_column_filter_text',
                            'sortable'     => true,
                        ));
                        break;
                    case 'select':
                        $selectOptions = array();
                        $options       = explode(',', $attachment->getOptions());
                        $options       = array_map('trim', $options);
                        if ($options)
                        {
                            foreach ($options as $option)
                            {
                                $selectOptions[$option] = $option;
                            }
                        }
                        $this->addColumn($attachment->getFieldname(), array(
                            'header'       => $this->__($attachment->getLabel()),
                            'index'        => $attachment->getFieldname(),
                            'filter_index' => 'attachments.'.$attachment->getFieldname(),
                            'type'         => 'options',
                            'options'      => $selectOptions,
                        ));
                        break;
                }
            }
        }
        
        if ($actionsColumn)
        {
            $this->_columns['action'] = $actionsColumn;
        }
        
        return $this;
    }
}