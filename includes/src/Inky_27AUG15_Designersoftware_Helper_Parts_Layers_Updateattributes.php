<?php

class Inky_Designersoftware_Helper_Parts_Layers_Updateattributes extends Mage_Core_Helper_Abstract
{	
	public function updateAttributes($dataPartsLayers){
		//echo '<pre>';print_r($dataColor);exit;
		$partsLayersIdsArray = unserialize($dataPartsLayers['update_parts_layers_ids']);		
		
		$data = array();
		$model = Mage::getModel('designersoftware/parts_layers');		
		foreach($partsLayersIdsArray as $partsLayersId){
			$status=0;		
			if(!empty($dataPartsLayers['status'])){$data['status']=$dataPartsLayers['status'];$status=1;}
			
			//if(!empty($dataPartsLayers['default_color_id'])){$data['default_color_id']=$dataPartsLayers['default_color_id'];$status=1;}
			
			if(isset($dataPartsLayers['links'])){
				$colors = Mage::helper('adminhtml/js')->decodeGridSerializedInput($dataPartsLayers['links']['color']);				
				if(!empty($colors)):
					/*$colorIdArray = array_keys($colors);
					if(!isset($data['default_color_id']) || !in_array($data['default_color_id'],$colorIdArray)):
						$data['default_color_id'] 	= 	$colorIdArray[0];
					endif;*/
					$data['color_price'] 		= serialize($dataPartsLayers['color_price']);
					$data['color_ids'] 			= serialize(array_keys($colors));
				else:
					$data['color_ids'] 			= '';
					$data['default_color_id'] 	= '';
				endif;
				
				$texture = Mage::helper('adminhtml/js')->decodeGridSerializedInput($dataPartsLayers['links']['texture']);
				if(!empty($colors)):
					$data['texture_ids'] = serialize(array_keys($texture));
				else:
					$data['texture_ids'] = '';
				endif;	
				$status=1;	
			}
			
			if($status==1){ 
				$data['update_time'] = now();
				$model->setData($data)
					->setId($partsLayersId);
					
				$model->save();
			}			
		}
	}
}
