<?php

class Inky_Designersoftware_Helper_Store_Parts_Style extends Mage_Core_Helper_Abstract {
	
	const INKY_STORE_PARTS_STYLE = 'inky_store_parts_style';
	
	public function getDataById($partsStyleId){
		$readConnection = Mage::helper('designersoftware/connection')->read();		
		
		$query = "SELECT * FROM " . self::INKY_STORE_PARTS_STYLE . " WHERE parts_style_id=$partsStyleId";
		$results = $readConnection->fetchAll($query);
		
		return $results;
	}
	
	public function getQueryByRow($keyId, $storeId){
		$readConnection = Mage::helper('designersoftware/connection')->read();		
		
		$query = "SELECT * FROM " . self::INKY_STORE_PARTS_STYLE . " WHERE parts_style_id=$keyId AND store_id=$storeId";
		$results = $readConnection->fetchAll($query);
		
		return $results;
	}
	
	public function getValue($id, $storeId){
		$result = $this->getQueryByRow($id, $storeId);
		return $result[0];
	}
	
	public function setDataById($data){		
		$writeConnection = Mage::helper('designersoftware/connection')->write();		
				
		$partsStyleId 	= $data['parts_style_id'];
		$titleArr 		= $data['title'];	
		
		foreach($titleArr as $storeId => $title):			
			$results 		= $this->getQueryByRow($partsStyleId, $storeId);
			if(count($results)>0):
				$query = "UPDATE " . self::INKY_STORE_PARTS_STYLE . " SET title='$title' WHERE parts_style_id=$partsStyleId AND store_id=$storeId";
			else:
				$query = "INSERT INTO " . self::INKY_STORE_PARTS_STYLE . " SET parts_style_id=$partsStyleId, title='$title', store_id=$storeId";
			endif;
			$writeConnection->query($query);		
		endforeach;		
	}
	
}
