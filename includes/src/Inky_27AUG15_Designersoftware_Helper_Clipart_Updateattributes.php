<?php

class Inky_Designersoftware_Helper_Clipart_Updateattributes extends Mage_Core_Helper_Abstract
{	
	public function updateAttributes($dataClipart){
		//echo '<pre>';print_r($model);exit;
		$clipartIdsArray = unserialize($dataClipart['update_clipart_ids']);
		//echo '<pre>';print_r($dataClipart);exit;
		
		$data = array();
		$model = Mage::getModel('designersoftware/clipart');		
		foreach($clipartIdsArray as $clipartId){
			$status=0;
			if(!empty($dataClipart['clipart_category_id'])){$data['clipart_category_id']=$dataClipart['clipart_category_id'];$status=1;}
			if(!empty($dataClipart['colorable'])|| $dataClipart['colorable']==0){$data['colorable']=$dataClipart['colorable'];$status=1;}
			if(!empty($dataClipart['status'])){$data['status']=$dataClipart['status'];$status=1;}
			if($status==1){ 
				$data['update_time'] = now();
				$model->setData($data)
					->setId($clipartId);
					
				$model->save();
			}			
		}
	}
}
