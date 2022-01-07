<?php

class Inky_Designersoftware_Helper_Connection extends Mage_Core_Helper_Abstract {
	
	public function getResource(){
		$resource = Mage::getSingleton('core/resource');
		return $resource;		
	}
	
	public function read(){
		$resource = $this->getResource();
		$readConnection = $resource->getConnection('core_read');
		
		return $readConnection;
	}
	
	public function write(){
		$resource = $this->getResource();
		$writeConnection = $resource->getConnection('core_write');
		
		return $writeConnection;	
	}
	
}
