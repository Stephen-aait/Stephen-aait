<?php
class Inky_Designersoftware_UploadController extends Mage_Core_Controller_Front_Action
{    
    public function IndexAction()
    {   	
		//echo '<pre>';print_r($_FILES);exit;
		$fieldName = 'ImageFile';		

		if(isset($_FILES[$fieldName]['name']) && $_FILES[$fieldName]['name'] != '' && $_FILES[$fieldName]['size'] <= 20 * 1024 * 1024) {
			 try {	                       
                        /* Starting upload */	
                        $uploader = new Varien_File_Uploader($fieldName);
                        
                        // Any extention would work
						$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png','eps','svg','bmp','pdf'));
						$uploader->setAllowRenameFiles(false);
						
						// Set the file upload mode 
						// false -> get the file directly in the specified folder
						// true -> get the file in the product like folders 
						//	(file.jpg will go in something like /media/f/i/file.jpg)
						$uploader->setFilesDispersion(false);
						// We set media/files as Default Image Folder for Designer Software Images
						// The below function will return directory path for saving original Image
						// it will be like - media/inky/{{controllerName}}/original//{{filename}}
						
						$path = Mage::helper('designersoftware/image')->createModuleImageDirectories('clipart_upload','original');
						
						$unquieName=uniqid('UP-') . rand(1, 100);
						
						$clipartTitle = pathinfo($_FILES[$fieldName]['name'], PATHINFO_FILENAME);
						$extension = pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION);
						$newFilename = $unquieName . '.' . $extension;
												
						$uploader->save($path, $newFilename );						
						
						$maintainRatio = true;
						$imgTypeArr = Mage::helper('designersoftware/image')->getThumbImage($newFilename,224,224,$maintainRatio,'clipart_upload'); 
						$imgTypeArr = Mage::helper('designersoftware/image')->getThumbImage($newFilename,51,53,$maintainRatio,'clipart_upload'); 
				  } catch (Exception $e) {
						Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				  }
				  
				  //this way the name is saved in DB
				  $data['title']		= $clipartTitle;
				  $data['customer_id']	= Mage::helper('designersoftware/customer')->getCurrentCustomerId();
				  $data['session_id']	= Mage::helper('designersoftware/customer')->getCurrentSessionId();
				  $data['filename'] 	= $newFilename;
				  $data['status']		= 1;
				  $data['created_time']	= now();
				  $data['update_time']	= now();
				  
	  			  $model = Mage::getModel('designersoftware/clipart_upload');		
				  $model->setData($data);
				  $model->save();
				  
			$returnArray['thumb'] 		= $imgTypeArr['filename'];
			$returnArray['maxH'] 		= $imgTypeArr['width'];
			$returnArray['maxW'] 		= $imgTypeArr['height'];
			$returnArray['errorStr'] 	= 'No';
						
			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($returnArray));
		 }			
    } 
    
    public function imagesAction(){
		$JSON = Mage::helper('designersoftware/upload_images_json')->load($this->getRequest()->getParams());		
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($JSON));
	}
}
