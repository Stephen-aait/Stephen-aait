<?php

class Inky_Designersoftware_Helper_Store_Parts_Layers extends Mage_Core_Helper_Abstract {
	
	const INKY_STORE_PARTS_LAYERS = 'inky_store_parts_layers';
	
	public function getDataById($partsLayersId){
		$readConnection = Mage::helper('designersoftware/connection')->read();		
		
		$query = "SELECT * FROM " . self::INKY_STORE_PARTS_LAYERS . " WHERE parts_layers_id=$partsLayersId";
		$results = $readConnection->fetchAll($query);
		
		return $results;
	}
	
	public function getQueryByRow($keyId, $storeId){
		$readConnection = Mage::helper('designersoftware/connection')->read();		
		
		$query = "SELECT * FROM " . self::INKY_STORE_PARTS_LAYERS . " WHERE parts_layers_id=$keyId AND store_id=$storeId";
		$results = $readConnection->fetchAll($query);
		
		return $results;
	}
	
	public function getValue($id, $storeId){
		$result = $this->getQueryByRow($id, $storeId);
		return $result[0];
	}
	
	public function setDataById($data){		
		$writeConnection = Mage::helper('designersoftware/connection')->write();		
				
		$partsLayersId 	= $data['parts_layers_id'];
		$titleArr 		= $data['title'];	
		
		foreach($titleArr as $storeId => $title):			
			$results 		= $this->getQueryByRow($partsLayersId, $storeId);
			if(count($results)>0):
				$query = "UPDATE " . self::INKY_STORE_PARTS_LAYERS . " SET title='$title' WHERE parts_layers_id=$partsLayersId AND store_id=$storeId";
			else:
				$query = "INSERT INTO " . self::INKY_STORE_PARTS_LAYERS . " SET parts_layers_id=$partsLayersId, title='$title', store_id=$storeId";
			endif;
			$writeConnection->query($query);		
		endforeach;		
	}
	
}
