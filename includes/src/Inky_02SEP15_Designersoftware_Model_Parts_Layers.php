<?php

class Inky_Designersoftware_Model_Parts_Layers extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('designersoftware/parts_layers');
    }
    
    public function getPartsLayersCollection(){		
		$collection = Mage::getModel('designersoftware/parts_layers')->getCollection()
							->addFieldToFilter('status',1);							
							
		return $collection;
	}
		
	public function getCollectionById($id){
		$collection = $this->getPartsLayersCollection();
		
		$collection = $collection->addFieldToFilter('parts_layers_id',$id)						
						->getFirstItem();	
							
		return $collection;		
	}
	
	public function getColorCollectionById($partsLayersId){
		$collection = $this->getPartsLayersCollection();
		
		$collection = $collection->addFieldToFilter('parts_layers_id',$partsLayersId)						
						->getFirstItem();	
							
		return unserialize($collection->getColorIds());
	}
	
	public function getCollectionByCode($partsLayersCode){
		$collection = $this->getPartsLayersCollection();
		
		$collection = $collection->addFieldToFilter('layer_code',$partsLayersCode)						
						->getFirstItem();	
							
		return $collection;		
	}
	
	public function getCollectionByPartsStyleId($partsStyleId=''){
		$collection = $this->getPartsLayersCollection();
		if(!empty($partsStyleId)):
			$collection = $collection->addFieldToFilter('parts_style_id',$partsStyleId);		
			return $collection;
		else:
			return false;		
		endif;			
	}
	
	public function getColorPrice($partsLayersId, $colorId){
		$collection = $this->getPartsLayersCollection();
		
		if(!empty($partsLayersId)):
			$collection = $collection->addFieldToFilter('parts_layers_id',$partsLayersId)->getFirstItem();
			$colorPrice = $collection->getData('color_price');	
				
			if(empty($colorPrice)):	
				$defaultColorPrice = $collection->getData('default_color_price');	
							
				if($defaultColorPrice>0):
					return $defaultColorPrice;
				else:	
					return 0;
				endif;
			else:
				$color_price = unserialize($collection->getData('color_price'));						
				if(!empty($color_price[$colorId]) && $color_price[$colorId]>0):
					return $color_price[$colorId];
				else:
					$defaultColorPrice = $collection->getData('default_color_price');
					
					if($defaultColorPrice>0):
						return $defaultColorPrice;
					else:	
						return 0;
					endif;	
				endif;
			endif;
		else:
			return false;		
		endif;	
		
	}
	
	public function getColorInfoColorPrice($partsLayersId, $colorId){
		$collection = $this->getPartsLayersCollection();
		if(!empty($partsLayersId)):
			$collection = $collection->addFieldToFilter('parts_layers_id',$partsLayersId)->getFirstItem();
			$colorPrice = $collection->getData('color_price');		
			if(empty($colorPrice)):
				return false;
			else:
				$color_price = unserialize($collection->getData('color_price'));						
				if(!empty($color_price[$colorId]) && $color_price[$colorId]>0):
					return $color_price[$colorId];
				endif;				
			endif;
		else:
			return false;		
		endif;	
	}
}
