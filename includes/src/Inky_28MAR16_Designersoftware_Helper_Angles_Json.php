<?php

class Inky_Designersoftware_Helper_Angles_Json extends Mage_Core_Helper_Abstract
{
	public function load($data){
		$anglesCollection  = Mage::getModel('designersoftware/angles')->getCollection()->addFieldToFilter('status',1);						
		foreach($anglesCollection as $angle):		
			$array['id']	= $angle->getId();
			$array['name']	= $angle->getTitle();
			
			$anglesDetails[] = $array;
		endforeach;
		
		return $anglesDetails;
	}
}
