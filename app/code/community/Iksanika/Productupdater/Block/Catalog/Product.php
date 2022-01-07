<?php

/**
 * Iksanika llc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.iksanika.com/products/IKS-LICENSE.txt
 *
 * @category   Iksanika
 * @package    Iksanika_Productupdater
 * @copyright  Copyright (c) 2013 Iksanika llc. (http://www.iksanika.com)
 * @license    http://www.iksanika.com/products/IKS-LICENSE.txt
 */

class Iksanika_Productupdater_Block_Catalog_Product extends Mage_Adminhtml_Block_Catalog_Product
{
    
    public function __construct()
    {
        parent::__construct();
        $this->_headerText = Mage::helper('productupdater')->__('Products Manager (Advanced)');
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->_addButton('add_new', array(
            'label'   => Mage::helper('catalog')->__('Add Product'),
            'onclick' => "setLocation('{$this->getUrl('adminhtml/catalog_product/new')}')",
            'class'   => 'add'
        ));
        $this->setTemplate('iksanika/productupdater/catalog/product.phtml');
        $this->setChild('grid', $this->getLayout()->createBlock('productupdater/catalog_product_grid', 'product.productupdater'));
        $this->setChild('store_switcher', $this->getLayout()->createBlock('adminhtml/store_switcher'));
    }
    
    public function getStoreSwitcherHtml()
    {
        if(!$this->isSingleStoreMode())
        {
            return $this->getChildHtml('store_switcher');
        }
    }
}