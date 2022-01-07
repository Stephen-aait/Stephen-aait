<?php

/**
 * @category    Genmato
 * @package     Genmato_PendingOrderGrid
 * @copyright   Copyright (c) 2013 Genmato BV (https://genmato.com)
 */
class Genmato_PendingOrderGrid_Block_System_Config_Form_Field_Massactions extends Genmato_Core_Block_System_Config_Form_Field_Array_Abstract
{

    public function __construct()
    {

        $this->addColumn(
            'action',
            array(
                'label' => Mage::helper('genmato_pendingordergrid')->__('Action'),
                'type' => 'select',
                'options' => Mage::getModel('genmato_pendingordergrid/massactions')->toOptionArray(),
                'style' => 'width:200px',
            )
        );

        $this->addColumn(
            'title',
            array(
                'label' => Mage::helper('genmato_pendingordergrid')->__('Title'),
                'type' => 'text',
                'style' => 'width:100px',
            )
        );

        $this->_addAfter = true;
        $this->_addButtonLabel = Mage::helper('genmato_pendingordergrid')->__('Add Mass Action');
        $this->setTemplate('genmato/core/system/config/form/field/array.phtml');
        parent::__construct();
    }
}