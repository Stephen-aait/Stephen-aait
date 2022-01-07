<?php

/**********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-11-03, 10:36:28)
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

class Modulesgarden_Base_Model_Mage {
	
	CONST RATIO_CUSTOM_EXTENSION		= 10;	// Custom Extension (+X for custom customization)
	CONST RATIO_CORE_EDITED                 = 300;	// Core Files Edited (app/code/core/Mage)
	CONST RATIO_CORE_INDEX_EDITED		= 150;	// Core Files Edited (index.php)
	CONST RATIO_CORE_OLD_VERSION		= 50;	// < 1.7.0.2
	CONST RATIO_MAIN_MODULE_OVERWRITTEN	= 100;	// Main Model Overwritten (Order, Quote, Customer, Product)
	CONST RATIO_ANY_MODEL_OVERWRITTEN	= 10;
	CONST RATIO_ANY_BLOCK_OVERWRITTEN	= 5;
	
	// md5sum of app/code/core/Mage - find app/code/core/Mage -type f | xargs cat | md5sum
	protected static $_MD5SUMS_CORE = array(
		'1.9.1.0 CE' => '77e90f4bf4dc8391b91585fe8a742bbc',
		'1.9.0.1 CE' => '1176ba35905e7ea5ac01d3ab5a10748e',
		'1.9.0.0 CE' => 'f90e40b9cf7b4977e7570029b50fdf52',
		'1.8.1.0 CE' => 'd0147d3811847e091e04322a93ca780d',
		'1.7.0.2 CE' => '23b6c42a764c3d05018b568693585cb4',
		'1.6.2.0 CE' => '42b05c40dccfdbe492f559d28142dfb1',
	);
	
	// md5sum of /index.php
	protected static $_MD5SUMS_CORE_INDEX = array(
		'1.9.1.0 CE' => '07f30524368e5f15ed8a2200525f694f',
		'1.9.0.1 CE' => '73d62a354e51517afd247faa731f8ad3',
		'1.9.0.0 CE' => '73d62a354e51517afd247faa731f8ad3',
		'1.8.1.0 CE' => '73d62a354e51517afd247faa731f8ad3',
		'1.7.0.2 CE' => '73d62a354e51517afd247faa731f8ad3',
		'1.6.2.0 CE' => '20b1633bd332a08e643afa4590ab8564',
	);
        
        /* Min ratio values for labels */
        public static $ratioLabels = array(
            0   => 'Clear Installation',
            30  => 'Almost Clear Installation',
            100 => 'Slightly Modified',
            350 => 'Modified',
            600 => 'Significantly Modified',
        );
	
	protected static $_calculation;
	
	/**
	 * How much system is customized (non-standard)
	 */
	public function calculateCustomization(){
		if (self::$_calculation){
			return self::$_calculation;
		}
		
		$extensions = Mage::getResourceModel('modulesgardenbase/extension')->getNonDefaultCollection();
		
		$customization = array(
			'magento' => array(
				'version' => Mage::getVersion() . ' ' . (Mage::helper('core')->isModuleEnabled('Enterprise_Enterprise') ? 'EE' : 'CE'),
				'index' => 'UNKNOWN',
				'core' => 'UNKNOWN',
			),
			'extensions' => array(),
			'models' => array(),
			'blocks' => array(),
			'ratio' => $extensions->getSize() * self::RATIO_CUSTOM_EXTENSION
		);
		foreach ($extensions as $ext){
			$customization['extensions'][] = $ext->getName() . ' ' . $ext->getVersion();
		}
		
		// === check models and blocks rewrites ===
		$modelsRewrites = Mage::getConfig()->getNode()->xpath('//global/models//rewrite');
		$blocksRewrites = Mage::getConfig()->getNode()->xpath('//global/blocks//rewrite');
		
		foreach ($modelsRewrites as $extensionRewrite){
			foreach ($extensionRewrite as $rewKey => $rewObject){
				$customization['models'][(string)$rewKey] = (string)$rewObject;
				$customization['ratio'] += self::RATIO_ANY_MODEL_OVERWRITTEN;
			}
		}
		
		foreach ($blocksRewrites as $blockRewrite){
			foreach ($blockRewrite as $rewKey => $rewObject){
				$customization['blocks'][(string)$rewKey] = (string)$rewObject;
				$customization['ratio'] += self::RATIO_ANY_BLOCK_OVERWRITTEN;
			}
		}
		
		// === check main models ===
		if (get_class(Mage::getModel('catalog/product')) != 'Mage_Catalog_Model_Product')
			$customization['ratio'] += self::RATIO_MAIN_MODULE_OVERWRITTEN;
		if (get_class(Mage::getModel('sales/order')) != 'Mage_Sales_Model_Order')
			$customization['ratio'] += self::RATIO_MAIN_MODULE_OVERWRITTEN;
		if (get_class(Mage::getModel('sales/quote')) != 'Mage_Sales_Model_Quote')
			$customization['ratio'] += self::RATIO_MAIN_MODULE_OVERWRITTEN;
		if (get_class(Mage::getModel('customer/customer')) != 'Mage_Customer_Model_Customer')
			$customization['ratio'] += self::RATIO_MAIN_MODULE_OVERWRITTEN;
		
		// === check whether core was edited ===
		if (isset(self::$_MD5SUMS_CORE[$customization['magento']['version']])){
			$md5sumCore = $this->_getMd5Sum(MAGENTO_ROOT.'/app/code/core/Mage');
			if ($md5sumCore == self::$_MD5SUMS_CORE[$customization['magento']['version']]){
				$customization['magento']['core'] = 'OK';
			} else {
				$customization['magento']['core'] = 'WRONG: ' . $md5sumCore;
				$customization['ratio'] += self::RATIO_CORE_EDITED;
			}
		}
		// === check whether index.php was edited ===
		if (isset(self::$_MD5SUMS_CORE_INDEX[$customization['magento']['version']])){
			$md5sumIndex = $this->_getMd5Sum(MAGENTO_ROOT.'/index.php');
			if ($md5sumIndex == self::$_MD5SUMS_CORE_INDEX[$customization['magento']['version']]){
				$customization['magento']['index'] = 'OK';
			} else {
				$customization['magento']['index'] = 'WRONG: ' . $md5sumIndex;
				$customization['ratio'] += self::RATIO_CORE_INDEX_EDITED;
			}
		}
		
		// === check whether magento version is old ===
		if (!Mage::helper('core')->isModuleEnabled('Enterprise_Enterprise')){
			if (version_compare(Mage::getVersion(), '1.7.0.2', '<')){
				$customization['ratio'] += self::RATIO_CORE_OLD_VERSION;
			}
		}
		// @todo checking EE version
		
		self::$_calculation = $customization;
		return self::$_calculation;
	}
	
	protected function _getMd5Sum($path){
		$commandReturn = exec('find '.$path.' -type f | xargs cat | md5sum'); // | sort -u
		return trim(str_replace('  -', '', $commandReturn));
	}
	
}
