<?php

class Inky_Designersoftware_Helper_Parts_Style_Json extends Mage_Core_Helper_Abstract
{
	public function load($data){	
		$partsStyleCollection = Mage::getModel('designersoftware/parts_style')->getPartsStyleCollection();
		
		foreach($partsStyleCollection as $partsStyle):
			$array['partsStyleId'] 		= $partsStyle->getId();
			$array['partsStyleName'] 	= $partsStyle->getTitle();
			$array['partsStyleCode'] 	= $partsStyle->getCode();	
			$array['price'] 			= $partsStyle->getPrice();	
			$array['partIdenti']		= $this->getPartsCollectionById($partsStyle->getPartsId());		
			
			$partsArr[] = $array;
		endforeach;					
		
		return $partsArr;		
	}
	
	public function getPartsCollectionById($id){
		$collection = Mage::getModel('designersoftware/parts')->getCollectionById($id);
		
		return $collection->getTitle();
	}
}
