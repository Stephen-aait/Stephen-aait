<?php

class Inky_Designersoftware_Helper_Store_Parts extends Mage_Core_Helper_Abstract {
	
	const INKY_STORE_PARTS = 'inky_store_parts';
	
	public function getDataById($partsId){
		$readConnection = Mage::helper('designersoftware/connection')->read();		
		
		$query = "SELECT * FROM " . self::INKY_STORE_PARTS . " WHERE parts_id=$partsId";
		$results = $readConnection->fetchAll($query);
		
		return $results;
	}
	
	public function setDataById($data){		
		$writeConnection = Mage::helper('designersoftware/connection')->write();		
				
		$partsId 	= $data['parts_id'];
		$titleArr 	= $data['title'];
		$results 	= $this->getDataById($partsId);
		if(count($results)>0):
			foreach($titleArr as $storeId => $title):			
				$query = "UPDATE " . self::INKY_STORE_PARTS . " SET title='$title' WHERE parts_id=$partsId AND store_id=$storeId";
				$writeConnection->query($query);		
			endforeach;
		else:
			foreach($titleArr as $storeId => $title):			
				$query = "INSERT INTO " . self::INKY_STORE_PARTS . " SET parts_id=$partsId, title='$title', store_id=$storeId";
				$writeConnection->query($query);		
			endforeach;
		endif;
	}
	
}
