<?php

class Inky_Designersoftware_Helper_Designersoftware_Updateattributes extends Mage_Core_Helper_Abstract
{	
	public function updateAttributes($dataDesignersoftware){
		//echo '<pre>';print_r($dataDesignersoftware);exit;
		$designersoftwareIdsArray = unserialize($dataDesignersoftware['update_designersoftware_ids']);		
		
		$data = array();
		$model = Mage::getModel('designersoftware/designersoftware');		
		foreach($designersoftwareIdsArray as $designersoftwareId){
			$status=0;		
			if(!empty($dataDesignersoftware['title'])){$data['title']=$dataDesignersoftware['title'];$status=1;}
			if(!empty($dataDesignersoftware['category_id'])){$data['category_id']=$dataDesignersoftware['category_id'];$status=1;}
			if(!empty($dataDesignersoftware['status'])){$data['status']=$dataDesignersoftware['status'];$status=1;}
			if(!empty($dataDesignersoftware['gallery_status'])){$data['gallery_status']=$dataDesignersoftware['gallery_status'];$status=1;}
			if(!empty($dataDesignersoftware['remove_status'])){$data['remove_status']=$dataDesignersoftware['remove_status'];$status=1;}			
			
			if($status==1){ 
				$data['update_time'] = now();
				$model->setData($data)
					->setId($designersoftwareId);
					
				$model->save();
			}			
		}
	}
}
