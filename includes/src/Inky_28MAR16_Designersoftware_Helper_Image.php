<?php

class Inky_Designersoftware_Helper_Image extends Mage_Core_Helper_Abstract
{
	const MAIN_DIRECTORY 		= 'inky';
	const ORIGINAL_DIRECTORY 	= 'original';
	const THUMB_DIRECTORY 		= 'thumb';
	const DEFAULT_ANGLE_NAME	= '000';	
	const LAYER_IMAGE_EXT 		= '.png';
	const COMPOSITE_IMAGE_EXT	= '.png';
	const CLIPART_NEW_IMAGE_EXT = '.png';
	
	
	// This function is used to define final image Extension composited 
	public function getImageExt(){
		return self::COMPOSITE_IMAGE_EXT; 
	}
	
	protected function getLayerImageExt(){
		return self::LAYER_IMAGE_EXT;		
	}
	
		
	protected function getCompositeImageExt(){
		return self::COMPOSITE_IMAGE_EXT;		
	}	
	
	
	public function _imageMagicPath(){
		system('which convert'); 
	}
	
	
	
	// Get Main Directory Path To Save DesignerSoftware Images
	public function _mainDirPath(){
		// Main Directory Path to Save Images for DesignerSoftware
		return Mage::getBaseDir('media') . DS . self::MAIN_DIRECTORY . DS;
	}
	
	
	
	// Get Controller Directory Path
	private function _controllerDirPath($controllerName=''){
		
		// Get Directory Path Controller Wise to Save Images
		if(empty($controllerName) || $controllerName=='')
			$controllerName = $this->getControllerName();	
		
		//echo 'AD'.$this->_mainDirPath() . $controllerName . DS;exit;		
		return $this->_mainDirPath() . $controllerName . DS;
	}
	
	
	
	// Get Original Directory Path
	public function _originalImageDirPath($controllerName='', $originalDirectoryName=''){
		
		// Get Controller Path of Original Saved Image
		if(empty($controllerName))
			$controllerName = $this->getControllerName();	
		
		// Get Directory Path of Original Saved Image
		//if(empty($originalDirectoryName))
			//$originalDirectoryName = self::ORIGINAL_DIRECTORY;
		if(!empty($originalDirectoryName)):
			$originalDirectoryName = self::ORIGINAL_DIRECTORY;
			return $this->_controllerDirPath($controllerName) . $originalDirectoryName . DS;
		else:
			return $this->_controllerDirPath($controllerName);
		endif;
					
		//return $this->_controllerDirPath($controllerName) . $originalDirectoryName . DS;
		//return $this->_controllerDirPath($controllerName);
		
	}
	
	
	
	// Get Thumb Directory Path
	public function _thumbDirPath($width=0, $height=0, $controllerName=''){
					
		return $this->_controllerDirPath($controllerName) . $width.'X'.$height . DS ;
	}
	
	
	
	public function _mainWebPath(){
		return Mage::getBaseUrl('media') . self::MAIN_DIRECTORY . DS;
	}
	
	
	
	// Get Original Directory Path
	public function _originalWebPath($controllerName=''){
		if(empty($controllerName))
			$controllerName = $this->getControllerName();
				
		//return $this->_controllerWebPath($controllerName). self::ORIGINAL_DIRECTORY . DS;
		return $this->_controllerWebPath($controllerName);
	}
	
	
	
	public function _controllerWebPath($controllerName=''){
		if(empty($controllerName))
			$controllerName = $this->getControllerName();
			
		return $this->_mainWebPath() . $controllerName . DS ;
	}
	
	
	
	public function _thumbWebPath($width=0, $height=0, $controllerName=''){
		return $this->_controllerWebPath($controllerName) . $width.'X'.$height . DS ;
	}
	
	
	
	//Get Thumbnail of any preffered size
	public function getThumbImage($filename, $width=0, $height=0, $maintainRatio = true, $controllerName=''){		
		$imageDetails['filename'] = $filename;
		// Original Image File with Full Directory Path
		$originalImageDirPathFilename 	= $this->_originalImageDirPath($controllerName,'original') . $filename;
		
		if(file_exists($originalImageDirPathFilename)):		
			$image = new Imagick($originalImageDirPathFilename);
							
			//create thumb directory first
			$thumbImageDirPath		 		= $this->_controllerDirPath( $controllerName ) . $width.'X'.$height. DS;
			$this->createDirectory($thumbImageDirPath); 
			
			// File type check
			$extension = pathinfo($filename, PATHINFO_EXTENSION);			
			if($extension=='eps' || $extension=='svg' || $extension=='pdf'):
				$filename = pathinfo($filename, PATHINFO_FILENAME) . self::CLIPART_NEW_IMAGE_EXT;
				$image->setImageFormat("png");
				$originalImageDirPathFilename = $this->_originalImageDirPath( $controllerName , 'original' ) . $filename;				
				$image->writeImage($originalImageDirPathFilename);
				$imageDetails['filename'] = $filename;
			endif;
			
			// thumb Path
			$thumbImageDirPathFilename 		= $thumbImageDirPath . $filename;						
			
			if($maintainRatio):
			
				$thumbSize = $this->getNewImageRatio($width, $height,$originalImageDirPathFilename);	
				$width = $thumbSize['width'];
				$height = $thumbSize['height'];	
				
				$imageDetails['width'] = $width;
				$imageDetails['height'] = $height;
							
			endif;
			
			// Get Thumbnail of a image to preferred sizes 		
			$image->thumbnailImage($width, $height);
			$image->writeImage($thumbImageDirPathFilename);
			
			return $imageDetails;
			
		endif;			
			
	}
	
	public function getBasename($filename){
		return preg_replace('/^.+[\\\\\\/]/', '', $filename);
	}
	
	public function resizeImage($_imageUrl, $newImageUrl, $width, $height){
		//echo $newImageUrl;exit;
		// This will create Directory tree to the given path		
		$dir = dirname($newImageUrl).'/';
		//echo $newImageUrl;exit;
		if ( !file_exists( $dir ) ) {
			mkdir( $dir, 0777, true );
		}
		
		$imageName = $this->getBasename($newImageUrl);
		$imageResized = $dir . $imageName;
		//echo $_imageUrl;exit;
		
		if ( file_exists($_imageUrl) ):	
			if ( file_exists($imageResized) ):
				unlink($imageResized);
			endif;
			//copy($_imageUrl,$imageResized) or die('does not move');
			//echo $_imageUrl.'<br>';
			//$imageResized = '/media/public_html/public_html/INKY1003/media/test.png';
			$imageObj = new Varien_Image($_imageUrl);			
			$imageObj->constrainOnly(TRUE);
			$imageObj->keepAspectRatio(TRUE);
			$imageObj->keepFrame(TRUE);
			$imageObj->keepTransparency(TRUE);
			//$imageObj->backgroundColor('#dcd9d4');
			$imageObj->resize($width,$height);
			$imageObj->save($imageResized);	
						
		endif;
	}
	
	
	public function getThumbByFullImageDirPath($imageFullDirPath, $filename=self::DEFAULT_ANGLE_NAME, $width=0, $height=0){
		
		$imageFullDirPathFilename 	= $imageFullDirPath . $filename . self::COMPOSITE_IMAGE_EXT;
		$thumbImageDirPath 			= $imageFullDirPath . self::THUMB_DIRECTORY . DS;
		
		$this->createDirectory($thumbImageDirPath);
		$thumbImageDirPathFilename 	= $thumbImageDirPath . $filename . self::COMPOSITE_IMAGE_EXT;
		
		$image = new Imagick($imageFullDirPathFilename);
		
		// Get Thumbnail of a image to preferred sizes 		
		$image->thumbnailImage($width, $height);
		
		$fp = fopen($thumbImageDirPathFilename, 'w');
		fwrite($fp, $image);
		fclose($fp);
		
		return $thumbImageDirPathFilename;
		
	}
    
    
    public function getControllerName(){	
		$controllerName = Mage::app()->getRequest()->getControllerName();
		if(strpos($controllerName,'adminhtml_')!==false){
			
			$arr = explode('dminhtml_',$controllerName);
			return $arr[1];
			
		} else {
			
			return $controllerName;
			
		}
	}
    
    //params['upper']='UP-BK-3';
    //params['sole']='SL-BK-4';
    
    public function createCompositeImageDirPath($params){
		
		$path['dir'] = $this->_mainDirPath();
		$path['web'] = $this->_mainWebPath();		
		$path['partialPath'] = 	'';	
		
		foreach($params as $key=>$pathDir):
			 $path['dir'] .= $pathDir . DS;
			 $path['web'] .= $pathDir . DS;
			 $path['partialPath'] .= $pathDir . DS;
			 
			 $this->createDirectory($path['dir']);
		endforeach;	
		
		return $path;			
	}	
    
    
    public function createCompositeStyleDesignDirPath($styleDesignCode, $partsLayersIdArray){
		$path['dir'] = $this->_mainDirPath();
		$path['web'] = $this->_mainWebPath();
		$path['partialPath'] = '';
		
		$path['dir'] .= $styleDesignCode . DS;
		$path['web'] .= $styleDesignCode . DS;
		$path['partialPath'] .= $styleDesignCode . DS;		
			
		$this->createDirectory($path['dir']);	
		foreach($partsLayersIdArray as $id):
			$collection = Mage::getModel('designersoftware/parts_layers')->getCollection()
									->addFieldToFilter('parts_layers_id',$id)
									->addFieldToFilter('status',1)
									->getFirstItem();
			
			$partsCollection = Mage::getModel('designersoftware/parts')->getCollectionById($collection->getPartsId());
			$partsDataCollection = $partsCollection->getData();
			
			if(count($partsDataCollection)>0):						
				$path['dir'] .= $collection->getLayerCode() . DS;
				$path['web'] .= $collection->getLayerCode() . DS;
				$path['partialPath'] .= $collection->getLayerCode() . DS;			
				
				$this->createDirectory($path['dir']);		
			endif;
		endforeach;
		
		return $path;			
	}
	
	
	public function createCompositeStyleDesignLayersCodeDirPath($styleDesignCode, $layersCodeArray){
		$path['dir'] = $this->_mainDirPath();
		$path['web'] = $this->_mainWebPath();
		$path['partialPath'] = '';
		
		$path['dir'] .= $styleDesignCode . DS;
		$path['web'] .= $styleDesignCode . DS;
		$path['partialPath'] .= $styleDesignCode . DS;		
			
		$this->createDirectory($path['dir']);	
		foreach($layersCodeArray as $code):
			$partsCollection = Mage::getModel('designersoftware/parts')->getCollectionById($code['partId']);
			$partsDataCollection = $partsCollection->getData();
			
			if(count($partsDataCollection)>0):		
				$path['dir'] .= $code['code'] . DS;
				$path['web'] .= $code['code'] . DS;
				$path['partialPath'] .= $code['code'] . DS;			
				
				$this->createDirectory($path['dir']);
			endif;		
		endforeach;
		
		return $path;			
	}
    
    
    public function createModuleImageDirectories($controllerName='', $originalDirectoryName=''){
		
		$this->createDirectory( $this->_mainDirPath() );
		$this->createDirectory( $this->_controllerDirPath( $controllerName ) );
		
		if(!empty($originalDirectoryName)):
			$this->createDirectory( $this->_originalImageDirPath( $controllerName, $originalDirectoryName ) );	
			return $this->_originalImageDirPath( $controllerName, $originalDirectoryName );
		else:
			return $this->_controllerDirPath( $controllerName );
		endif;
				
	}
	
	
	
	public function createDirectory($dirName) {
	   	if (!is_dir($dirName)) {
			@mkdir($dirName);
			@chmod($dirName, 0777);
		}
    }
    
    
    
    function getNewImageRatio($ratioW, $ratioH, $imgPath) {
        $imageRatio = '';

        list($width, $height) = @(getimagesize($imgPath));
		
		if($width >= $height && $ratioW >= $ratioH):
		
			$ratio = $width / $height;
			$imageRatio['width'] = $ratioW;
            $imageRatio['height'] = $ratioW / $ratio;
            
        elseif($width > $height && $ratioH > $ratioW):
			
			$ratio = $height / $width;
			$imageRatio['width'] = $ratioH;
            $imageRatio['height'] = $ratioH * $ratio;
            
        elseif($height >= $width && $ratioH >= $ratioW):
        
			$ratio = $height / $width;
			$imageRatio['width'] = $ratioW / $ratio;
            $imageRatio['height'] = $ratioH;
            
        elseif($height > $width && $ratioW > $ratioH):
			
			$ratio = $height / $width;
			$imageRatio['width'] = $ratioW;
            $imageRatio['height'] = $ratioW * $ratio;
			
        else:
        
			$imageRatio['width'] = $ratioW;
            $imageRatio['height'] = $ratioH	;
            
		endif;       

        return $imageRatio;
    }
}
