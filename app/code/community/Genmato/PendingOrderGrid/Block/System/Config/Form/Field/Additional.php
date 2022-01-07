<?php

/**
 * @category    Genmato
 * @package     Genmato_PendingOrderGrid
 * @copyright   Copyright (c) 2013 Genmato BV (https://genmato.com)
 */
class Genmato_PendingOrderGrid_Block_System_Config_Form_Field_Additional extends Genmato_Core_Block_System_Config_Form_Field_Array_Abstract
{

    public function __construct()
    {

        $this->addColumn(
            'title',
            array(
                'label' => Mage::helper('genmato_pendingordergrid')->__('Title'),
                'type' => 'text',
                'style' => 'width:150px',
            )
        );

        $this->addColumn(
            'field',
            array(
                'label' => Mage::helper('genmato_pendingordergrid')->__('Status'),
                'type' => 'multiselect',
                'options' => Mage::getModel('genmato_pendingordergrid/system_config_source_order_status')->toOptionHash(
                ),
                'style' => 'width:250px',
            )
        );

        $this->_addAfter = true;
        $this->_addButtonLabel = Mage::helper('genmato_pendingordergrid')->__('Add Grid');
        $this->setTemplate('genmato/core/system/config/form/field/array.phtml');
        parent::__construct();
    }
}