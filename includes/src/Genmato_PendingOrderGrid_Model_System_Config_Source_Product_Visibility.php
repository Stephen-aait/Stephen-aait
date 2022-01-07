<?php

/**
 * @category    Genmato
 * @package     Genmato_PendingOrderGrid
 * @copyright   Copyright (c) 2014 Genmato BV (https://genmato.com)
 */
class Genmato_PendingOrderGrid_Model_System_Config_Source_Product_Visibility
{

    CONST PRODUCT_SHOW_ALL = 0;
    CONST PRODUCT_IDENT_CHILD = 1;
    CONST PRODUCT_HIDE_PARENT = 2;

    public function toOptionArray()
    {
        $options = array();
        $options[] = array(
            'value' => self::PRODUCT_SHOW_ALL,
            'label' => Mage::helper('genmato_pendingordergrid')->__('Show all products')
        );
        $options[] = array(
            'value' => self::PRODUCT_IDENT_CHILD,
            'label' => Mage::helper('genmato_pendingordergrid')->__('Ident subproducts')
        );
        $options[] = array(
            'value' => self::PRODUCT_HIDE_PARENT,
            'label' => Mage::helper('genmato_pendingordergrid')->__('Hide parent (configurable, grouped)')
        );

        return $options;
    }

    public function toOptionHash()
    {
        $options = array();
        $options[self::PRODUCT_SHOW_ALL] = Mage::helper('genmato_pendingordergrid')->__('Show all products');
        $options[self::PRODUCT_IDENT_CHILD] = Mage::helper('genmato_pendingordergrid')->__('Ident subproducts');
        $options[PRODUCT_HIDE_PARENT] = Mage::helper('genmato_pendingordergrid')->__(
            'Hide parent (configurable, grouped)'
        );

        return $options;
    }

}