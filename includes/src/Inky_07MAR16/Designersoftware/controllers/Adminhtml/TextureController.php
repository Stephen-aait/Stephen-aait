<?php

class Inky_Designersoftware_Adminhtml_TextureController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('designersoftware/texture')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Texture Manager'), Mage::helper('adminhtml')->__('Texture Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('designersoftware/texture')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('texture_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('designersoftware/texture');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Texture Manager'), Mage::helper('adminhtml')->__('Texture Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Texture News'), Mage::helper('adminhtml')->__('Texture News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('designersoftware/adminhtml_texture_edit'))
				->_addLeft($this->getLayout()->createBlock('designersoftware/adminhtml_texture_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('designersoftware')->__('Texture does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
		
	public function checkDuplicateCode($code){
		$model = Mage::getModel('designersoftware/texture')->getCollection()
							->addFieldToFilter('texture_code',$code)
							->getData();
		if(count($model)>0):
			return false;
		else:
			return true;
		endif;
	}
	
	public function saveAction() {		
		
		if ($data = $this->getRequest()->getPost()) {				
			
			
			if(!$this->checkDuplicateCode($data['texture_code'])):
				Mage::getSingleton('adminhtml/session')
						->addError(Mage::helper('designersoftware')->__('Duplicate Texture Code, Please enter unique value.'));
						
				$this->_redirect('*/*/new');
				return;
			endif;			
			
			$model = Mage::getModel('designersoftware/texture');
					
			if(isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {
				try {	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('filename');
					
					// Any extention would work
	           		$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
					
					// Set the file upload mode 
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders 
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(false);
							
					// We set media/files as Default Image Folder for Designer Software Images
					// The below function will return directory path for saving original Image
					// it will be like - media/files/{{controllerName}}/original//{{filename}}
					$path = Mage::helper('designersoftware/image')->createModuleImageDirectories('','original');
					
					// Logic to generate Dynamic Name for Image and will use the same name every time when image will be entered
					$id = $this->getRequest()->getParam('id');					
					if(empty($id)){
						$unquieName=uniqid('T-') . rand(1, 100);
						$newFilename = $unquieName . '.' . pathinfo($_FILES['filename']['name'], PATHINFO_EXTENSION);
					} else {
						$collection = $model->getCollection()->addFieldToFilter('texture_id', $id)->getFirstItem();
						//echo '<pre>';print_r($collection);exit;
						$newFilename = $collection->getFilename();
						if(!file_exists(Mage::helper('designersoftware/image')->_originalImageDirPath() . $newFilename) || empty($newFilename)){
							$unquieName=uniqid('T-') . rand(1, 100);
							$newFilename = $unquieName . '.' . pathinfo($_FILES['filename']['name'], PATHINFO_EXTENSION);
						}
					}
					
					$uploader->save( $path, $newFilename );
					
					//Thumb Images
					//Below will generate thumb images of entred sizes
					//it will create thumb and save images in path like - media/files/{{controllerName}}/{{Width}}X{{Height}}/{{filename}}
					Mage::helper('designersoftware/image')->getThumbImage($newFilename,224,224);
					Mage::helper('designersoftware/image')->getThumbImage($newFilename,200,200);
					Mage::helper('designersoftware/image')->getThumbImage($newFilename,45,45);
					
				} catch (Exception $e) {
		      
		        }
	        
		        //this way the name is saved in DB
	  			$data['filename'] = $newFilename;
			}  			
			
					
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
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('designersoftware')->__('Texture was successfully saved'));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('designersoftware')->__('Unable to find texture to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('designersoftware/texture');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Texture was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $designersoftwareIds = $this->getRequest()->getParam('texture');
        if(!is_array($designersoftwareIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select texture(s)'));
        } else {
            try {
                foreach ($designersoftwareIds as $designersoftwareId) {
                    $designersoftware = Mage::getModel('designersoftware/texture')->load($designersoftwareId);
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
        $designersoftwareIds = $this->getRequest()->getParam('texture');
        if(!is_array($designersoftwareIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select texture(s)'));
        } else {
            try {
                foreach ($designersoftwareIds as $designersoftwareId) {
                    $designersoftware = Mage::getSingleton('designersoftware/texture')
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
        $fileName   = 'designersoftware_texture.csv';
        $content    = $this->getLayout()->createBlock('designersoftware/adminhtml_texture_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'designersoftware_texture.xml';
        $content    = $this->getLayout()->createBlock('designersoftware/adminhtml_texture_grid')
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
