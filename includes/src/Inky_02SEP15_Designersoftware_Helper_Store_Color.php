<?php

class Inky_Designersoftware_Helper_Store_Color extends Mage_Core_Helper_Abstract {
	
	const INKY_STORE_COLOR = 'inky_store_color';
	
	public function getDataById($colorId){
		$readConnection = Mage::helper('designersoftware/connection')->read();		
		
		$query = "SELECT * FROM " . self::INKY_STORE_COLOR . " WHERE color_id=$colorId";
		$results = $readConnection->fetchAll($query);
		
		return $results;
	}
	
	public function getQueryByRow($keyId, $storeId){
		$readConnection = Mage::helper('designersoftware/connection')->read();		
		
		$query = "SELECT * FROM " . self::INKY_STORE_COLOR . " WHERE color_id=$keyId AND store_id=$storeId";
		$results = $readConnection->fetchAll($query);
		
		return $results;
	}
	
	public function getValue($id, $storeId){
		$result = $this->getQueryByRow($id, $storeId);
		return $result[0];
	}
	
	public function setDataById($data){		
		$writeConnection = Mage::helper('designersoftware/connection')->write();		
				
		$colorId 	= $data['color_id'];
		$titleArr 	= $data['title'];	
		
		foreach($titleArr as $storeId => $title):			
			$results 		= $this->getQueryByRow($colorId, $storeId);
			if(count($results)>0):
				$query = "UPDATE " . self::INKY_STORE_COLOR . " SET title='$title' WHERE color_id=$colorId AND store_id=$storeId";
			else:
				$query = "INSERT INTO " . self::INKY_STORE_COLOR . " SET color_id=$colorId, title='$title', store_id=$storeId";
			endif;
			$writeConnection->query($query);		
		endforeach;		
	}
	
}
