<?php

class Inky_Designersoftware_Adminhtml_FontController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('designersoftware/font')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Font Manager'), Mage::helper('adminhtml')->__('Font Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('designersoftware/font')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('font_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('designersoftware/font');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Font Manager'), Mage::helper('adminhtml')->__('Font Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Font News'), Mage::helper('adminhtml')->__('Font News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('designersoftware/adminhtml_font_edit'))
				->_addLeft($this->getLayout()->createBlock('designersoftware/adminhtml_font_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('designersoftware')->__('Font does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
            
		if ($data = $this->getRequest()->getPost()) {
                    			
                        // To save swf file format
                        //include('ycTIN_TTF.php');
                        $i=0;
                        foreach($_FILES as $key => $filename){
                            $imageName = $this->saveTtfFiles($key);
                            //echo '<pre>';print_r($imageName);exit;
                            foreach($imageName as $keyIn => $value){
                                $data[$keyIn] = $value;
                            }
                        }
                        
                   //   echo '<pre>';
                   //	print_r($data); exit;	
                        
			$model = Mage::getModel('designersoftware/font');
                        $model->setData($data)
				->setId($this->getRequest()->getParam('id'));
                                               
			
			try {
				if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}	
				
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('designersoftware')->__('Font was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
                            } catch (Exception $e) {
                                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                                Mage::getSingleton('adminhtml/session')->setFormData($data);
                                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('designersoftware')->__('Unable to find Font to save'));
        $this->_redirect('*/*/');
	}
        public function saveTtfFiles($filename){
			
            // To save ttf file format 
            if(isset($_FILES[$filename]['name']) && $_FILES[$filename]['name'] != '') {
                try {	
						//echo '<pre>';
						//print_r($_FILES[$filename]);exit;	
                        /* Starting upload */	
                        $uploader = new Varien_File_Uploader($filename);

                        // Any extention would work
                        $uploader->setAllowedExtensions(array('ttf'));
                        $uploader->setAllowRenameFiles(false);

                        // Set the file upload mode 
                        // false -> get the file directly in the specified folder
                        // true -> get the file in the product like folders 
                        //	(file.jpg will go in something like /media/f/i/file.jpg)
                        $uploader->setFilesDispersion(false);

                        // We set media as the upload dir
                        //$path = Mage::getBaseDir('media') . DS ;
                        //$uploader->save($path, $_FILES['filename_ttf']['name'] );

                        // We set media as the upload dir
                        if($filename=='filename_boldttf'){
                            $pathTTF = Mage::getBaseDir('media') . DS . 'inky' .DS . 'font' . DS . 'ttf/bold' . DS;
                        } else if($filename=='filename_italicttf'){
                            $pathTTF = Mage::getBaseDir('media') . DS . 'inky' .DS . 'font' . DS . 'ttf/italic' . DS;
                        } else if($filename=='filename_bolditalicttf'){
                            $pathTTF = Mage::getBaseDir('media') . DS . 'inky' .DS . 'font' . DS . 'ttf/bolditalic' . DS;
                        } else {
                            $pathTTF = Mage::getBaseDir('media') . DS . 'inky' .DS . 'font' . DS . 'ttf' . DS;
                        }
                        
                        $newFileName = 	str_replace(" ","_",$_FILES[$filename]['name']);
                        $uploader->save($pathTTF, $newFileName);
						

                        // Creating PNG image of ttf font  // Creation of font image starts
                        $fontImgName = date("Ymd") . '-' . uniqid();
                        $fontImgName = $fontImgName . '.png';
                        
                        if($filename=='filename_boldttf'){
                            $pathTTFImg = Mage::getBaseDir('media') . DS . 'inky' . DS . 'font' . DS . 'image/bold' . DS . $fontImgName;
                            $imageName['boldttf_image_name'] = $fontImgName;
                        } else if($filename=='filename_italicttf'){
                            $pathTTFImg = Mage::getBaseDir('media') . DS . 'inky' . DS . 'font' . DS . 'image/italic' . DS . $fontImgName;
                             $imageName['italicttf_image_name'] = $fontImgName;
                        } else if($filename=='filename_bolditalicttf'){
                            $pathTTFImg = Mage::getBaseDir('media') . DS . 'inky' . DS . 'font' . DS . 'image/bolditalic' . DS . $fontImgName;
                             $imageName['bolditalicttf_image_name'] = $fontImgName;
                        } else {
                            $pathTTFImg = Mage::getBaseDir('media') . DS . 'inky' . DS . 'font' . DS . 'image' . DS . $fontImgName;
                            $imageName['ttf_image_name'] = $fontImgName;
                        }
                                                
                        //$ttfObj = new ycTIN_TTF(); //create yctin_ttf object
                        //$resultObj = $ttfObj->generateFontImage($pathTTF . $_FILES[$filename]['name'], $_FILES[$filename]['name'], $pathTTFImg);
                        $resultObj = Mage::helper('designersoftware/font_ttf')->generateFontImage($pathTTF . $newFileName, $newFileName, $pathTTFImg);
                        $imageName[$filename] = $newFileName;
                        $imageName['title'] = $resultObj['fontStyle'];
                                                	
                } catch (Exception $e) {

                }
            }
            
            return $imageName;
                       
        }
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('designersoftware/font');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Font was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $designersoftwareIds = $this->getRequest()->getParam('font');
        if(!is_array($designersoftwareIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select font(s)'));
        } else {
            try {
                foreach ($designersoftwareIds as $designersoftwareId) {
                    $designersoftware = Mage::getModel('designersoftware/font')->load($designersoftwareId);
                    $designersoftware->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($designersoftwareIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $designersoftwareIds = $this->getRequest()->getParam('font');
        if(!is_array($designersoftwareIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select font(s)'));
        } else {
            try {
                foreach ($designersoftwareIds as $designersoftwareId) {
                    $designersoftware = Mage::getSingleton('designersoftware/font')
                        ->load($designersoftwareId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($designersoftwareIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'designersoftware_font.csv';
        $content    = $this->getLayout()->createBlock('designersoftware/adminhtml_font_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'designersoftware_font.xml';
        $content    = $this->getLayout()->createBlock('designersoftware/adminhtml_font_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}
