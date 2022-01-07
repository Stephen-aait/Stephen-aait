<?php

/**********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-10-30, 13:23:34)
 * 
 *
 *  CREATED BY MODULESGARDEN       ->        http://modulesgarden.com
 *  CONTACT                        ->       contact@modulesgarden.com
 *
 *
 *
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 *
 *
 **********************************************************************/

/**
 * @author Grzegorz Draganik <grzegorz@modulesgarden.com>
 */

class Modulesgarden_Base_Model_Observer {
	
	protected $_mgSections = array(
		'mgbase_installed_extensions' => 'Installed Extensions',
		'mgbase_store' => 'Store'
	);
	
	public function adminhtml_block_html_before(Varien_Event_Observer $observer){
		$block = $observer->getEvent()->getBlock();
		$section = Mage::app()->getRequest()->getParam('section');
		if ($block instanceof Mage_Adminhtml_Block_System_Config_Edit && in_array($section, array_keys($this->_mgSections))){
			if ($section == 'mgbase_installed_extensions')
				$block->unsetChild('save_button');
			$block->setTitle('<img src="'.Mage::getBaseUrl('skin').'/adminhtml/base/default/modulesgardenbase/img/mgcommerce-logo.png" style="vertical-align: bottom;" /> ' . $block->__($this->_mgSections[$section]));
			$block->setHeaderCss('modulesgardenbase_config_header');
		}
	}
	
	/**
	 * By Cron
	 */
	public function fetchNotifications(){
            
		$events = explode(',', Mage::getStoreConfig('mgbase_store/notifications/events'));
		$client = new Modulesgarden_Base_Model_Extension_Client();
		
		$version = (string)Mage::getConfig()->getNode()->modules->Modulesgarden_Base->version;
		$helper = Mage::helper('modulesgardenbase');
		$resource = Mage::getResourceModel('modulesgardenbase/extension');
		
		$client->registerModuleInstance($version,  $_SERVER['SERVER_ADDR'], $_SERVER['SERVER_NAME']);
		
		if (in_array(Modulesgarden_Base_Model_System_Config_Source_Notificationevents::UPGRADES, $events)){
			foreach ($resource->getModulesgardenCollection() as $installedMgExtension){
				if ($installedMgExtension->isUpgardeAvailable()){
					$msgTitle = $helper->__('Upgarde Of "%s" Extension From ModulesGarden Is Available (%s)', $installedMgExtension->getFriendlyName(), $installedMgExtension->getLatestVersion() );
					
					if (!Modulesgarden_Base_Model_Adminnotification_Inbox::exists($msgTitle)){
						Mage::getModel('modulesgardenbase/adminnotification_inbox')->addMajor(
							$msgTitle,
							$helper->__('New version: %s. Download extension from modulesgarden.com and install it in your magento.', $installedMgExtension->getLatestVersion()),
							$installedMgExtension->getChangelogUrl()
						);
					}
					
				}
			}
		}
		if (in_array(Modulesgarden_Base_Model_System_Config_Source_Notificationevents::RELEASES, $events)){
			$extensionsFromStore = Mage::getResourceModel('modulesgardenbase/extension')->getExtensionsObjectsFromModulesgardenCom();
			foreach ($extensionsFromStore as $extensionFromStore){
				if ($extensionFromStore->getIsNew()){
					$msgTitle = $helper->__('New Extension From ModulesGarden: %s', $extensionFromStore->getFriendlyName());
					
					if (!Modulesgarden_Base_Model_Adminnotification_Inbox::exists($msgTitle)){
						Mage::getModel('modulesgardenbase/adminnotification_inbox')->addMajor(
							$msgTitle,
							$helper->__('Download extension from modulesgarden.com and install it in your magento.'),
							$extensionFromStore->getChangelogUrl()
						);
					}
					
				}
			}
		}
		if (in_array(Modulesgarden_Base_Model_System_Config_Source_Notificationevents::PROMOTIONS, $events)){
			$promotions = $client->getActivePromotions();
			if ($promotions && isset($promotions->data->promotions)){
				foreach ($promotions->data->promotions as $promo){
					$msgTitle = $helper->__('New Promotion From ModulesGarden: %s', $promo->notes);
					
					if (!Modulesgarden_Base_Model_Adminnotification_Inbox::exists($msgTitle)){
						Mage::getModel('modulesgardenbase/adminnotification_inbox')->addMajor(
							$msgTitle,
							$helper->__('Promotion Code: %s', $promo->code),
							'http://www.modulesgarden.com'
						);
					}
				}
			}
		}
	}
	
}
