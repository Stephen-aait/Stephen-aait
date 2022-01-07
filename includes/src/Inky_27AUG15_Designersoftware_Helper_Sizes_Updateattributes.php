<?php

class Inky_Designersoftware_Helper_Sizes_Updateattributes extends Mage_Core_Helper_Abstract
{	
	public function updateAttributes($dataSizes){
		//echo '<pre>';print_r($dataColor);exit;
		$sizesIdsArray = unserialize($dataSizes['update_sizes_ids']);		
		
		$data = array();
		$model = Mage::getModel('designersoftware/sizes');		
		foreach($sizesIdsArray as $sizesId){
			$status=0;		
			if(!empty($dataSizes['status'])){$data['status']=$dataSizes['status'];$status=1;}
			
			//if(!empty($dataSizes['default_color_id'])){$data['default_color_id']=$dataSizes['default_color_id'];$status=1;}
			
			if(isset($dataSizes['links'])){
				$colors = Mage::helper('adminhtml/js')->decodeGridSerializedInput($dataSizes['links']['color']);				
				if(!empty($colors)):
					/*$colorIdArray = array_keys($colors);
					if(!isset($data['default_color_id']) || !in_array($data['default_color_id'],$colorIdArray)):
						$data['default_color_id'] 	= 	$colorIdArray[0];
					endif;*/
					$data['color_price'] 		= serialize($dataSizes['color_price']);
					$data['color_ids'] 			= serialize(array_keys($colors));
				else:
					$data['color_ids'] 			= '';
					$data['default_color_id'] 	= '';
				endif;
				
				$texture = Mage::helper('adminhtml/js')->decodeGridSerializedInput($dataSizes['links']['texture']);
				if(!empty($texture)):
					$data['texture_ids'] = serialize(array_keys($texture));
				else:
					$data['texture_ids'] = '';
				endif;	
				$status=1;	
			}
			
			if($status==1){ 
				$data['update_time'] = now();
				$model->setData($data)
					->setId($sizesId);
					
				$model->save();
			}			
		}
	}
}
