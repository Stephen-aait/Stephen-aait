<?php

/**
 * @category    Genmato
 * @package     Genmato_PendingOrderGrid
 * @copyright   Copyright (c) 2013 Genmato BV (https://genmato.com)
 */
class Genmato_PendingOrderGrid_Block_System_Config_Form_Field_Fields extends Genmato_Core_Block_System_Config_Form_Field_Array_Abstract
{

    public function __construct()
    {

        $this->addColumn(
            'field',
            array(
                'label' => Mage::helper('genmato_pendingordergrid')->__('Database Field'),
                'type' => 'select',
                'options' => Mage::getModel('genmato_pendingordergrid/fields')->toOptionArray(),
                'style' => 'width:100px',
            )
        );

        $this->addColumn(
            'title',
            array(
                'label' => Mage::helper('genmato_pendingordergrid')->__('Title'),
                'type' => 'text',
                'style' => 'width:150px',
            )
        );

        $this->addColumn(
            'width',
            array(
                'label' => Mage::helper('genmato_pendingordergrid')->__('Width (px)'),
                'type' => 'text',
                'style' => 'width:50px',
            )
        );

        $this->_addAfter = true;
        $this->_addButtonLabel = Mage::helper('genmato_pendingordergrid')->__('Add Field');
        $this->setTemplate('genmato/core/system/config/form/field/array.phtml');
        parent::__construct();
    }
}