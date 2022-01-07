<?php

class Inky_Designersoftware_Helper_Color_Updateattributes extends Mage_Core_Helper_Abstract
{	
	public function updateAttributes($dataColor){
		//echo '<pre>';print_r($dataColor);exit;
		$colorIdsArray = unserialize($dataColor['update_color_ids']);		
		
		$data = array();
		$model = Mage::getModel('designersoftware/color');		
		foreach($colorIdsArray as $colorId){
			$status=0;			
			if(!empty($dataColor['clip_status'])){$data['clip_status']=$dataColor['clip_status'];$status=1;}
			if(!empty($dataColor['text_status'])){$data['text_status']=$dataColor['text_status'];$status=1;}
			if(!empty($dataColor['status'])){$data['status']=$dataColor['status'];$status=1;}
			if($status==1){ 
				$data['update_time'] = now();
				$model->setData($data)
					->setId($colorId);
					
				$model->save();
			}			
		}
	}
}
