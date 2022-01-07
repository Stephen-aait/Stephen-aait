<?php

class Inky_Designersoftware_Helper_Store extends Mage_Core_Helper_Abstract {
	
	public function getAllStores(){
		$storeArr = array();
		foreach (Mage::app()->getWebsites() as $website) {
			foreach ($website->getGroups() as $group) {
				$stores = $group->getStores();
				foreach ($stores as $store) {					
					$storeArr['store_id'] 	= $store->getId();
					$storeArr['label'] 	= $store->getName();					
					
					$storesArr[] = $storeArr;
				}				
			}
		}
		
		return $storesArr;
	}
	
}
