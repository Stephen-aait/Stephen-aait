<?php

class Inky_Designersoftware_Helper_Store_Clipart extends Mage_Core_Helper_Abstract {
	
	const INKY_STORE_CLIPART = 'inky_store_clipart';
	
	public function getDataById($clipartId){
		$readConnection = Mage::helper('designersoftware/connection')->read();		
		
		$query = "SELECT * FROM " . self::INKY_STORE_CLIPART . " WHERE clipart_id=$clipartId";
		$results = $readConnection->fetchAll($query);
		
		return $results;
	}
	
	public function getQueryByRow($keyId, $storeId){
		$readConnection = Mage::helper('designersoftware/connection')->read();		
		
		$query = "SELECT * FROM " . self::INKY_STORE_CLIPART . " WHERE clipart_id=$keyId AND store_id=$storeId";
		$results = $readConnection->fetchAll($query);
		
		return $results;
	}
	
	public function getValue($id, $storeId){
		$result = $this->getQueryByRow($id, $storeId);
		return $result[0];
	}
	
	public function setDataById($data){		
		$writeConnection = Mage::helper('designersoftware/connection')->write();		
				
		$clipartId 	= $data['clipart_id'];
		$titleArr 	= $data['title'];	
		
		foreach($titleArr as $storeId => $title):			
			$results 		= $this->getQueryByRow($clipartId, $storeId);
			if(count($results)>0):
				$query = "UPDATE " . self::INKY_STORE_CLIPART . " SET title='$title' WHERE clipart_id=$clipartId AND store_id=$storeId";
			else:
				$query = "INSERT INTO " . self::INKY_STORE_CLIPART . " SET clipart_id=$clipartId, title='$title', store_id=$storeId";
			endif;
			$writeConnection->query($query);		
		endforeach;		
	}
	
}
