<?php

class Inky_Designersoftware_Helper_Parts_Layers_Color_Json extends Mage_Core_Helper_Abstract
{
	public function load($data){			
		$partsLayersId = $data['partsLayersId'];			
		//$partsLayersId = 1;			
		$clipartColorCollection  = Mage::getModel('designersoftware/parts_layers')->getColorCollectionById($partsLayersId);			
									
		foreach($clipartColorCollection as $colorId):		
			$color = Mage::getModel('designersoftware/color')->getColorCollectionById($colorId);
			
			if($color->getId()>0){
				$array['id']			= $color->getId();
				$array['title']			= $color->getTitle();
				$array['colorCode']		= $color->getColorcode();	
				$array['price']			= Mage::getModel('designersoftware/parts_layers')->getColorPrice($partsLayersId, $color->getId()); 		
				$array['texture']		= $this->textureImage($color->getTextureId());	//Texture Image Src
				$array['texturetitle']	= $this->textureTitle($color->getTextureId()); 	// Texture Title
				
				$colorDetails[] = $array;
			}
		endforeach;
			
			$finalJSON['colors'] = $colorDetails;
			
		return $finalJSON;
	}
	
	public function textureImage($id){
		$collection = Mage::getModel('designersoftware/texture')->getCollection()->addFieldToSelect('filename')->addFieldToFilter('texture_id',$id)->getFirstItem();
		
		return $collection->getFilename();
	}	
	
	public function textureTitle($id){
		$collection = Mage::getModel('designersoftware/texture')->getCollection()->addFieldToSelect('title')->addFieldToFilter('texture_id',$id)->getFirstItem();
		
		return $collection->getTitle();
	}
	
}
