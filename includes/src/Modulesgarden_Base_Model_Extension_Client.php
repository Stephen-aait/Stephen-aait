<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-10-30, 14:39:18)
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
 * ******************************************************************** */

/**
 * @author Mariusz Miodowski <mariusz@modulesgarden.com>
 * @author Grzegorz Draganik <grzegorz@modulesgarden.com>
 */
class Modulesgarden_Base_Model_Extension_Client {

	CONST MODULE_NAME = 'Modules Garden Widget For Magento';
	CONST MODULE_KEY = 'fhKzDqtZ3NloER4kV0olIHRIqba8VDf8';
	
	/**
	 *  Cache life time.
	 */
	protected $cache = array(
		'getLatestModuleVersion' => 43200, /* 12 hours */
		'registerModuleInstance' => 43200, /* 12 hours */
                'getAvailableProducts' => 3600, /* 1 hour */
		'getActivePromotions' => 3600, /* 1 hour */
	);
	//Server Location
	protected $url = 'https://modulesgarden.com/manage/modules/addons/ModuleInformation/server.php';
	//This name will be send to modulesgarden.com
	protected $module = '';
	//Module Name
	protected $moduleName = '';
	//Encryption Key
	protected $accessHash = '';
	//Error?
	protected $error = '';

	public function __construct($moduleName = '', $accessHash = '', $url = '') {
		$this->module = $moduleName;
		$this->moduleName = $moduleName ? strtolower(str_replace(' ', '', $moduleName)) : self::MODULE_NAME;
		$this->accessHash = $accessHash ? trim($accessHash) : self::MODULE_KEY;
		if ($url) {
			$this->url = $url;
		}
	}

	public function setModule($moduleName, $accessHash) {
		$this->module = $moduleName;
		$this->moduleName = strtolower(str_replace(' ', '', $moduleName));
		$this->accessHash = trim($accessHash);
	}

	public function setURL($url) {
		$this->url = $url;
	}

	/**
	 * @param type $currentVersion
	 */
	public function getLatestModuleVersion() {
		$request = array(
			'action' => 'getLatestModuleVersion',
		);

		return $this->send($request);
	}

	public function getActivePromotions() {
		$request = array(
			'action' => 'getActivePromotions'
		);

		return $this->send($request);
	}

	/**
	 * Register new module instance
	 * @param type $moduleVersion
	 * @param type $serverIP
	 * @param type $serverName
	 * @return type
	 */
	public function registerModuleInstance($moduleVersion, $serverIP, $serverName) {
		$request = array(
			'action' => 'registerModuleInstance',
			'data' => array
				(
				'moduleVersion' => $moduleVersion,
				'serverIP' => $serverIP,
				'serverName' => $serverName,
			)
		);

		return $this->send($request);
	}

	/**
	 * Get all available products
	 * @return type
	 */
	public function getAvailableProducts($platform = null) {
		$requst = array(
			'action' => 'getAvailableProducts',
			'data' => array(
				'platform' => $platform
			)
		);

		return $this->send($requst);
	}

	private function send($data = array()) {
		if (!$data) {
			return false;
		}

		if (empty($data['action'])) {
			return false;
		}

		//Add module name and access hash
		$data['hash'] = $this->accessHash;
		$data['module'] = $this->module;

		//Are we have ane cache?
                
                $json = $this->getFromCache($data);
                
                if( ! empty($json) ) {
                    return $json;
                }

		//Encode data
		$jsonData = json_encode($data);

		//Prepare Curl
		$ch = curl_init($this->url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_POSTREDIR, 3);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: text/xml'));

		$ret = curl_exec($ch);

		if (!$ret) {
			$this->error = 'Did not receive any data. ' . curl_error($ch);
			return false;
		}

		$json = json_decode($ret);
		if (!$json) {
			$this->error = 'Invalid Format';
			return false;
		}

		if (!$json->status) {
			$this->error = $json->message;
			return false;
		}
                
                $this->saveCache($data, $json);

		return $json;
	}

	public function getError() {
		return $this->error;
	}

	protected function setError($error) {
		$this->error = $error;
	}
        
        private function getCacheKeyByData($data) {
            return $this->moduleName . '_' . serialize($data);
        }
        
        private function saveCache($data, $json) {
            $key        = $this->getCacheKeyByData($data);
            $action     = $data['action'];
            
            if( isset($this->cache[$action]) ) {
                Mage::app()->getCache()->save(urlencode(serialize($json)), $key, array(), $this->cache[$action]);
            }
        }
        
        private function getFromCache($data) {
            $key    = $this->getCacheKeyByData($data);
            $object = Mage::app()->getCache()->load($key);
            
            if($object !== false) {
                return unserialize(urldecode($object));
            }
                
            return null;
        }

}