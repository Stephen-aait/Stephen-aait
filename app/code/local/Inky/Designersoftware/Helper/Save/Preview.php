<?php

class Inky_Designersoftware_Helper_Save_Preview extends Mage_Core_Helper_Abstract
{
	public function load($data){
		//echo '<pre>';print_r($data);exit;
		$designcode = $data['styleDesignCode'];
		$previewArr = $data['previewArr'];
		
		$count=0;
		$loopCount=1;
		$allAnglesCollection = Mage::getModel('designersoftware/angles')->getAnglesCollection()->getData();
		foreach($previewArr as $key=>$preview):
			//echo '<pre>';print_r($allAnglesCollection[$key]);exit;
			$anglesCollection = $allAnglesCollection[$key];			
			if($count==0):
				$corePath = Mage::getBaseDir('media') . DS . 'inky';
				Mage::helper('designersoftware/image')->createDirectory($corePath);
				$corePath = $corePath . DS . 'designs';
				Mage::helper('designersoftware/image')->createDirectory($corePath);
				$corePath = $corePath . DS . $designcode;
				Mage::helper('designersoftware/image')->createDirectory($corePath);			
				$orgPath = $corePath . DS . 'original';
				Mage::helper('designersoftware/image')->createDirectory($orgPath);			
				$count++;
			else:
				$corePath = Mage::getBaseDir('media') . DS . 'inky' . DS . 'designs' . DS . $designcode;
				$orgPath = Mage::getBaseDir('media') . DS . 'inky' . DS . 'designs' . DS . $designcode . DS . 'original';
			endif;
			
			$filename = $anglesCollection['title'].'.png';
			$orgPathFile = $orgPath . DS . $filename;			
			
			if($this->getPreviewImage($preview,$orgPathFile)):
				$canvas_image_width=35;							
				$canvas_image_height=138;
				Mage::helper('designersoftware/image')->resizeImage($orgPathFile,$corePath.DS.$canvas_image_width.'x'.$canvas_image_height.DS.$filename,35,138);
				$canvas_image_width=82;							
				$canvas_image_height=318;
				Mage::helper('designersoftware/image')->resizeImage($orgPathFile,$corePath.DS.$canvas_image_width.'x'.$canvas_image_height.DS.$filename,82,318);
				$canvas_image_width=82;							
				$canvas_image_height=318;
				Mage::helper('designersoftware/image')->resizeImage($orgPathFile,Mage::getBaseDir('media') . DS . 'catalog/product/designersoftware/'.$designcode.'/'.$filename,350,440);
				Mage::getModel('catalog/product_image')->clearCache();
			else:
				break;
				return false;
			endif;						
		endforeach;	
	}
	
	public function getPreviewImage($preview, $previewImgPath = ''){		
		$previewData = base64_decode(substr($preview, 22));		
		
		if(!empty($previewData)):			
			@unlink($previewImgPath);
			if (!file_exists($previewImgPath)):
				$fp = fopen($previewImgPath, 'w');
				fwrite($fp, $previewData);
				fclose($fp);				
				
				return true;
			endif;
		endif;			
		
		return false;
	}
}
