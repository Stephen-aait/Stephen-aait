<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */
class Amasty_Orderattach_Block_Adminhtml_Grid_Jsinit extends Mage_Adminhtml_Block_Template
{
    protected function _construct()
    {
        parent::_construct();

        if (Mage::helper('amorderattach')->isAllowedEdit())
            $this->setTemplate('amorderattach/js.phtml');
    }
    
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        return $this;
    }
    
    public function getSaveUrl()
    {
        $url = $this->getUrl('adminhtml/amorderattach_order/saveField');
        if (Mage::getStoreConfig('web/secure/use_in_adminhtml'))
        {
            $url = str_replace(Mage::getStoreConfig('web/unsecure/base_url'), Mage::getStoreConfig('web/secure/base_url'), $url);
        }
        return $url;
    }

    public function getColumnsProperties()
    {
        return Mage::helper('amorderattach')->getColumnsProperties();
    }
    
    public function getStoreId()
    {
        $storeId = (int) Mage::app()->getRequest()->getParam('store', 0);
        return $storeId;
    }
}