<?php

class Inky_Designersoftware_Helper_Data extends Mage_Core_Helper_Abstract
{	
	public function getTexture(){
		$textureArr=array();
		//$texturetypeArr['']='Select Category';
		$collection = Mage::getModel('designersoftware/texture')->getCollection()
			->addFieldToFilter('status',array('eq'=>1));
			//->setOrder('sort_order',asc);
		if($collection->getSize()>0){
			foreach($collection->getData() as $value){
				$textureArr[$value['texture_id']]= $value['title'];	
			}
		}
		return $textureArr;
	}
	
	public function getParts($includeNone = false){
		$partsArr=array();
		
		if ($includeNone){
			$partsArr['']='--- Please Select ---';
		}
		
		$collection = Mage::getModel('designersoftware/parts')->getCollection()
			->addFieldToFilter('status',array('eq'=>1));
			
		if($collection->getSize()>0){
			foreach($collection->getData() as $value){
				$partsArr[$value['parts_id']]=$value['title'];								
			}
		}
		return $partsArr;
	}
	
	public function getPartsStyle($includeNone = false){
		$partsStyleArr=array();
		
		if ($includeNone){
			$partsStyleArr['']='--- Please Select ---';
		}
		
		$collection = Mage::getModel('designersoftware/parts_style')->getCollection()
			->addFieldToFilter('status',array('eq'=>1))
			->setOrder('sort_order','ASC');
			
		if($collection->getSize()>0){
			foreach($collection->getData() as $value){
				$partsStyleArr[$value['parts_style_id']]=$value['title'];								
			}
		}
		return $partsStyleArr;
	}
	
	public function getLayerColors($parts_layers_id, $includeNone = false){
		$layerColorsArr=array();
		
		if ($includeNone){
			$layerColorsArr['']='--- Please Select ---';
		}
		
		$collection = Mage::getModel('designersoftware/parts_layers')->getCollection()
			->addFieldToFilter('parts_layers_id',$parts_layers_id)
			->addFieldToFilter('status',array('eq'=>1))
			->getFirstItem();
		
		if($collection->getId()>0){
			$colorIdArray = unserialize($collection->getColorIds());
			foreach($colorIdArray as $value){
				$collection = Mage::getModel('designersoftware/color')->getCollection()
										->addFieldToFilter('color_id',$value)
										->getFirstItem();
										
				$layerColorsArr[$collection->getColorcode()]=$collection->getTitle();								
			}
			
			return $layerColorsArr;			
		} else {
			return false;
		}					
	}
	
	public function getClipartCategory($includeNone = false){
		$clipartCategoryArr=array();
		
		if ($includeNone){
			$clipartCategoryArr['']='--- Please Select ---';
		}
		
		$collection = Mage::getModel('designersoftware/clipart_category')->getCollection()
			->addFieldToFilter('status',array('eq'=>1));
			
		if($collection->getSize()>0){
			foreach($collection->getData() as $value){
				$clipartCategoryArr[$value['clipart_category_id']]=$value['title'];								
			}
		}
		return $clipartCategoryArr;
	}
	
	
	public function getMultiSelectParts($inlcudeNone = false){
		$collection = Mage::getModel('designersoftware/parts')->getCollection()
			->addFieldToFilter('status',array('eq'=>1))
			->setOrder('sort_order','ASC');
			
		$values = array();
		if ($inlcudeNone){
			$values[] = array('label' => "--None--", 'value' => 0);
		}
		foreach ($collection as $parts) {
			$values[] = array('label' => $parts->getTitle(), 'value' => $parts->getId());
		}
		return $values;		
	}
	
	public function getPartsLayersTexture($partsLayersId){
		$partsLayersTextureArr=array();
		//$leathertypeArr['']='Select Category';
		$partsLayersCollection = Mage::getModel('designersoftware/parts_layers')->getCollection()			
			->addFieldToFilter('parts_layers_id',array('eq'=>$partsLayersId))
			->addFieldToFilter('status',array('eq'=>1))->getFirstItem();					
		
		if(count($partsLayersCollection->getData())>0){
			$textureIdArray = unserialize($partsLayersCollection->getTextureIds());				
			foreach ($textureIdArray as $texture) {	
				$textureCollection = Mage::getModel('designersoftware/texture')->getCollection()->addFieldToFilter('texture_id',$texture)->getFirstItem();				
				$partsLayersTextureArr[$textureCollection->getId()]=$textureCollection->getTitle();								
			}					
		}
		return $partsLayersTextureArr;
	}
	
	public function getPartsLayers($partsId=0){
		$partsTypeArr=array();
		//$leathertypeArr['']='Select Category';
		$collection = Mage::getModel('designersoftware/parts_layers')->getCollection();			
			if($partsId>0):
				$collection->addFieldToFilter('parts_id',array('eq'=>$partsId));
			endif;
		$collection->addFieldToFilter('status',array('eq'=>1));
			//->setOrder('sort_order','ASC');
			
		if($collection->getSize()>0){
			foreach($collection->getData() as $value){
				$partsTypeArr[$value['parts_layers_id']]=$value['title'];								
			}
		}
		return $partsTypeArr;
	}
		
	public function getPartsType($partsId){
		$partsTypeArr=array();
		//$leathertypeArr['']='Select Category';
		$collection = Mage::getModel('designersoftware/parts_type')->getCollection()
			->addFieldToFilter('parts_id',array('eq'=>$partsId))
			->addFieldToFilter('status',array('eq'=>1));
			//->setOrder('sort_order','ASC');
			
		if($collection->getSize()>0){
			foreach($collection->getData() as $value){
				$partsTypeArr[$value['parts_type_id']]=$value['title'];								
			}
		}
		return $partsTypeArr;
	}
	
	public function getColor(){
		$colorArr=array();
		//$leathertypeArr['']='Select Category';
		$collection = Mage::getModel('designersoftware/color')->getCollection()			
			->addFieldToFilter('status',array('eq'=>1));
			//->setOrder('sort_order','ASC');
			
		if($collection->getSize()>0){
			foreach($collection->getData() as $value){
				$colorArr[$value['colorcode']]=$value['title'];								
			}
		}
		return $colorArr;
	}
}
