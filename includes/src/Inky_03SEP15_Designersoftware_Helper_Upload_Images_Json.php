<?php

class Inky_Designersoftware_Helper_Upload_Images_Json extends Mage_Core_Helper_Abstract
{
	public function load($data){			
					
		$uploadCollectionByCustomer  = Mage::getModel('designersoftware/clipart_upload')->getCollectionByCustomer();	
							
		foreach($uploadCollectionByCustomer as $collection):		
			$array['id']		= $collection->getId();
			$array['title']		= $collection->getTitle();
			$array['thumb']		= $collection->getFilename();				
									
			$uplaodDetails[] = $array;
		endforeach;
			
			$finalJSON['uploadedImage'] = $uplaodDetails;
			
		return $finalJSON;
	}	
}
