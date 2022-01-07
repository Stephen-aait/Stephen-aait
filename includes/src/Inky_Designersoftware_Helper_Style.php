<?php

class Inky_Designersoftware_Helper_Style extends Mage_Core_Helper_Abstract {
	
	public function getStyles(){
		$collection = Mage::getModel('designersoftware/designersoftware')->getCollection()
							//->addFieldToFilter('sort_order',array('neq'=>0))							
							->addFieldToFilter('gallery_status',1)
							->addFieldToFilter('status',1)
							->setOrder('sort_order','ASC');
		
		return $collection;
	}     
	
	public function getStyleDesignImage($designId){		
		$collection = Mage::getModel('designersoftware/designersoftware')->getCollection()
							->addFieldToFilter('designersoftware_id',$designId)							
							->addFieldToFilter('status',1)
							->getFirstItem();

		//echo '<pre>';print_r($collection->getData());exit;
		$commPath = 'media' . DS . 'inky' . DS . 'designs' . DS . $collection->getStyleDesignCode() . DS . 'original' . DS;
        //$fileDirPath = Mage::getBaseDir() . DS . $commPath . '000.png';   
	    $fileWebPath = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . $commPath . '000.png'; 
	    
		//$imageUrl = Mage::helper('designersoftware/image')->createCompositeStyleDesignDirPath($collection->getCode(), unserialize($collection->getPartsLayersIds()));
		//echo $imageUrl['web'];exit;
		return $fileWebPath;
	}
	
	public function getStyleDesignCode($designId){		
		$collection = Mage::getModel('designersoftware/designersoftware')->getCollection()
							->addFieldToFilter('designersoftware_id',$designId)							
							->addFieldToFilter('status',1)
							->getFirstItem();
		
		$code = $collection->getStyleDesignCode();
		
		return $code;
	}
	
}
