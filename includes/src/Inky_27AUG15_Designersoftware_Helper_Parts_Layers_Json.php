<?php

class Inky_Designersoftware_Helper_Parts_Layers_Json extends Mage_Core_Helper_Abstract
{
	public function load($data){
		
		$collection = $this->getPartsStyleCollectionById($data['partsStyleId']);
		
		$partsLayersArr['partStyleId'] 		= $collection->getId();
		$partsLayersArr['partStyleCode'] 	= $collection->getCode();
		$partsLayersArr['layers'] 			= $this->getLayerDetailsByPartsStyle($collection->getId());
		
		$layersInfo['layersInfo'] 			=  $partsLayersArr;
		
		return $layersInfo;
		
	}
	
	public function getLayerDetailsByPartsStyle($partsStyleId){
		$partsLayersCollection = Mage::getModel('designersoftware/parts_layers')->getCollectionByPartsStyleId($partsStyleId);
		
		foreach($partsLayersCollection as $partsLayers):
			$array['layerId'] 			= $partsLayers->getId();
			$array['layerName'] 		= $partsLayers->getTitle();
			$array['layerCode'] 		= $partsLayers->getLayerCode();
			
			$collection = $this->getColorCollectionById($partsLayers->getDefaultColorId());
			$array['defaultColorTitle']	= $collection->getTitle();
			$array['defaultColorCode'] 	= $collection->getColorcode();
			
			$array['price'] 			= Mage::getModel('designersoftware/parts_layers')->getColorPrice($partsLayers->getId(), $partsLayers->getDefaultColorId());			
			
			$partsLayersArr[] = $array;
		endforeach;					
		
		return $partsLayersArr;	
	}
	
	public function getColorCollectionById($colorId){
		$collection = Mage::getModel('designersoftware/color')->getColorCollectionById($colorId);
		return $collection;
	}
	
	public function getColorcodeById($colorId){
		$code = Mage::getModel('designersoftware/color')->getColorcodeById($colorId);
		return $code;
	}
	
	public function getPartsStyleCollectionById($id){
		$collection = Mage::getModel('designersoftware/parts_style')->getCollectionById($id);
		
		return $collection;
	}
}
