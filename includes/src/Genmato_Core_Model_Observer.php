<?php
/**
 * @category    Genmato
 * @package     Genmato_Core
 * @copyright   Copyright (c) 2014 Genmato BV (https://genmato.com)
 */

class Genmato_Core_Model_Observer
{

    protected $refresh_timeout = 3600;

    /**
     * Predispath admin action controller
     *
     * @param Varien_Event_Observer $observer
     */
    public function preDispatch(Varien_Event_Observer $observer) {
        if (Mage::getSingleton('admin/session')->isLoggedIn()) {
            if(Mage::app()->loadCache('genmato_core_updated_flag')=='UPDATED') {
                if((time()-Mage::app()->loadCache('genmato_core_updated_time'))>$this->refresh_timeout) {
                    Mage::app()->removeCache('genmato_core_updated_flag');
                    Mage::helper('genmato_core')->registerExtensions();
                    Mage::app()->saveCache(time(), 'genmato_core_updated_time', array(), $this->refresh_timeout*2);
                }
            }
        }
    }
}