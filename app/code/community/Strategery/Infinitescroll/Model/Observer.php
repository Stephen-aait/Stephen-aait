<?php
/**
 * InfiniteScroll - Magento Integration
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0),
 * available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 *
 * @category   Strategery
 * @package    Strategery_Infinitescroll
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * @copyright  Copyright (c) 2014 Strategery Inc. (http://usestrategery.com)
 *
 * @author     Gabriel Somoza (gabriel@strategery.io)
 * @link       https://strategery.io
 *
 * @author     Damian A. Pastorini (damian.pastorini@strategery.io)
 *
 */
class Strategery_Infinitescroll_Model_Observer
{

    public function controllerActionPredispatch($event)
    {
        if (Mage::getSingleton('admin/session')->isLoggedIn()) {
            $feedModel = Mage::getModel('infinitescroll/admin_feed');
            $feedModel->checkUpdate();
        }

    }

}
