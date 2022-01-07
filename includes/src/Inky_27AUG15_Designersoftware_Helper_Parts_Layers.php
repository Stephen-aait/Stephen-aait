<?php
//Mage_Core_Helper_Abstract
class Inky_Designersoftware_Helper_Parts_Layers extends Inky_Designersoftware_Helper_Image
{
	public function getLayers($partsId, $styleDesignId){
		$collection = Mage::getModel('designersoftware/parts_layers')->getCollection()
									->addFieldToFilter('parts_id', $partsId)
									->addFieldToFilter('style_design_id', $styleDesignId)
									->addFieldToFilter('status',array('eq'=>1));
											
		if($collection->getSize()>0){
			foreach($collection->getData() as $value){
				$partsArr[$value['parts_layers_id']]=$value['layer_code'];								
			}
		}
		return $partsArr;
	}	
	
	public function getColorableDirPath($midPath, $angleName, $controllerName=''){		
		return $this->_originalImageDirPath($controllerName) . $midPath . DS . $angleName . self::LAYER_IMAGE_EXT;
	}	
	
	
	public function getColorableWebPath($midPath, $angleName,$controllerName=''){
		return $this->_originalWebPath($controllerName) . $midPath . DS . $angleName . self::LAYER_IMAGE_EXT;
	}
	
	
	// Function to Remove Images 
	public function removeLayers($layersArray, $midPath, $controllerName=''){
		if(count($layersArray) > 0):
			foreach($layersArray as $angleName=>$check):
				$path = $this->getColorableDirPath($midPath, $angleName, $controllerName);
				//echo $path;exit;
				unlink($path);
			endforeach;
		endif;
	}
			
}
