<?php

class Inky_Designersoftware_Helper_Font_Json extends Mage_Core_Helper_Abstract
{
	public function load($data){	
					
		$fontCollection  = Mage::getModel('designersoftware/font')->getFontCollection();	
							
		foreach($fontCollection as $font):				
			$array['fontsId']		= $font->getId();
			$array['title']			= $font->getTitle();
			$array['imagePath']		= $font->getTtfImageName();
			$array['fontTtf']		= $this->getFontType($font);					
			
			$clipartDetails[] = $array;
		endforeach;
			
			$finalJSON['fonts'] = $clipartDetails;
			
		return $finalJSON;
	}
	
	
	public function getFontType($font){
			$array[] = $this->getFontTypeDetails($font, '');
			$array[] = $this->getFontTypeDetails($font, 'bold');
			$array[] = $this->getFontTypeDetails($font, 'italic');
			$array[] = $this->getFontTypeDetails($font, 'bolditalic');					
		
		return $array;
		
	}
	
	public function getFontTypeDetails($font, $type){		
		$data = $font->getData();		
		$columnName = 'filename_'.$type.'ttf';
			
		$typeArray = explode('.',$data[$columnName]);
		$title = $typeArray[0];
					
		$array['fontTitle']			= $title;
		$array['fontDiff']			= $type==''? $type='normal': $type;
		$array['ttf']				= $data[$columnName];
		
		return $array;
	}
}
