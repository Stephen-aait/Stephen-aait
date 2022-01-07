<?php

class Inky_Designersoftware_Helper_System_Config extends Mage_Core_Helper_Abstract
{	
	public function getClipartUploadPrice(){		
		return Mage::getStoreConfig('designerfront/clipart/upload_price');		
	}
	public function getClipartColorPrice(){		
		return Mage::getStoreConfig('designerfront/clipart/color_price');		
	}
	
	
	public function getTextColorPrice(){		
		return Mage::getStoreConfig('designerfront/text/color_price');		
	}
}
