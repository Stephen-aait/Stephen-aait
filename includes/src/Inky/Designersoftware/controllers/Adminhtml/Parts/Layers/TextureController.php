<?php

class Inky_Designersoftware_Adminhtml_Parts_Layers_TextureController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('designersoftware/parts_layers_texture')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Texture Manager'), Mage::helper('adminhtml')->__('Texture Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}
	
	public function getPartsLayersAction() {
        $partsId = $this->getRequest()->getParam('parts_id');

        $partsLayers = Mage::getModel('designersoftware/parts_layers')->getCollection()                
                ->addFieldToFilter('status', 1)
                ->addFieldToFilter('parts_id', array('eq' => $partsId))
                ->getData();

        $_partsLayers = "<option value=''>Please Select</option>";
        if (count($partsLayers) > 0) {
            foreach ($partsLayers as $layer) {
                $_partsLayers .= "<option value='" . $layer['parts_layers_id'] . "'>" . stripslashes($layer['title']) . "</option>";
            }
        }
        
        echo $_partsLayers;
    }
    
    public function getTextureAction() {
        $partsId = $this->getRequest()->getParam('parts_id');		
        $partsLayersId = $this->getRequest()->getParam('parts_layers_id');		
		
		if(isset($partsId) && !empty($partsId)){
			$partsLayersTexture = Mage::getModel('designersoftware/parts_layers')->getCollection()                
					->addFieldToFilter('status', 1)
					->addFieldToFilter('parts_id', array('eq' => $partsId))
					->getData();
			
			$finalPartsLayersTextureArray = array();        
			if (count($partsLayersTexture) > 0) {
				foreach ($partsLayersTexture as $layerTexture) {

					$layerTextureArray = unserialize($layerTexture['texture_ids']);
					//print_r($layerTexture);exit;	
					if(is_array($layerTextureArray) && count($finalPartsLayersTextureArray)>0){
						if(is_array($layerTextureArray)){
							$finalPartsLayersTextureArray = array_unique(array_merge($finalPartsLayersTextureArray, $layerTextureArray));
						}	
					} else {
						$finalPartsLayersTextureArray = $layerTextureArray;					
					}
					unset($layerTextureArray);
				}
			}			
			
			//print_r($finalPartsLayersTextureArray);exit;              
						   
			$_partsLayersTexture = "<option value=''>Please Select</option>";        
			if (count($finalPartsLayersTextureArray) > 0) {
				foreach ($finalPartsLayersTextureArray as $layerTexture) {	
					$textureCollection = Mage::getModel('designersoftware/texture')->getCollection()->addFieldToFilter('texture_id',$layerTexture)->getFirstITem();
					//print_r($layerTexture);exit;
					$_partsLayersTexture .= "<option value='" . $textureCollection->getId() . "'>" . stripslashes($textureCollection->getTitle()) . "</option>";
				}
			}
		}
		
		// $parts Layers Request To Set Texture
		if(isset($partsLayersId) && !empty($partsLayersId)){
			$partsLayersTexture = Mage::getModel('designersoftware/parts_layers')->getCollection()                
					->addFieldToFilter('status', 1)
					->addFieldToFilter('parts_layers_id', array('eq' => $partsLayersId))
					->getFirstItem()
					->getData();
			
			$finalPartsLayersTextureArray = unserialize($partsLayersTexture['texture_ids']);       					
						   
			$_partsLayersTexture = "<option value=''>Please Select</option>";        
			if (count($finalPartsLayersTextureArray) > 0) {
				foreach ($finalPartsLayersTextureArray as $layerTexture) {	
					$textureCollection = Mage::getModel('designersoftware/texture')->getCollection()->addFieldToFilter('texture_id',$layerTexture)->getFirstItem();
					//print_r($layerTexture);exit;
					$_partsLayersTexture .= "<option value='" . $textureCollection->getId() . "'>" . stripslashes($textureCollection->getTitle()) . "</option>";
				}
			}
		}
        
        echo $_partsLayersTexture;
    }

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('designersoftware/parts_layers_texture')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('parts_layers_texture_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('designersoftware/parts_layers_texture');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Texture Manager'), Mage::helper('adminhtml')->__('Texture Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Texture News'), Mage::helper('adminhtml')->__('Texture News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('designersoftware/adminhtml_parts_layers_texture_edit'))
				->_addLeft($this->getLayout()->createBlock('designersoftware/adminhtml_parts_layers_texture_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('designersoftware')->__('Texture does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
		
	public function saveAction() {		
		
		if ($data = $this->getRequest()->getPost()) {							
			
			$model = Mage::getModel('designersoftware/parts_layers_texture');
			//echo '<pre>';print_r($data);print_r($_FILES);exit;					
					
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
				
				$partsCollection = Mage::getModel('designersoftware/parts')->getCollection()->addFieldToFilter('parts_id', $model->getPartsId())->addFieldToFilter('status',1)->getFirstItem()->getData();
				$partsLayersCollection = Mage::getModel('designersoftware/parts_layers')->getCollection()->addFieldToFilter('parts_layers_id', $model->getPartsLayersId())->addFieldToFilter('status',1)->getFirstItem();
				$textureCollection = Mage::getModel('designersoftware/texture')->getCollection()->addFieldToFilter('texture_id', $model->getTextureId())->addFieldToFilter('status',1)->getFirstItem();
								
				
				
				// Remove Images Action 				
				$midPath = $partsCollection['code'] . DS  .$partsLayersCollection->getLayerCode() . DS . $textureCollection->getTextureCode();
				$controllerName='parts_layers';
				Mage::helper('designersoftware/parts_layers')->removeLayers($data['delImage'], $midPath, $controllerName);
				
				
				
				if(count($_FILES['filename']['name'])>0):
					foreach($_FILES['filename']['name'] as $key=>$filename):			
						if(isset($filename) && $filename!= '') {
							try {	
								// Starting upload 
								$uploader = new Varien_File_Uploader('filename['.$key.']');
								
								// Any extention would work
								$uploader->setAllowedExtensions(array('png'));
								$uploader->setAllowRenameFiles(false);
								
								// Set the file upload mode 
								// false -> get the file directly in the specified folder
								// true -> get the file in the product like folders 
								//	(file.jpg will go in something like /media/f/i/file.jpg)
								$uploader->setFilesDispersion(false);
								
								$path = Mage::helper('designersoftware/image')->createModuleImageDirectories('parts_layers') . $partsCollection['code'] . DS;								
								Mage::helper('designersoftware/image')->createDirectory($path);							
																
								$path = Mage::helper('designersoftware/image')->createModuleImageDirectories('parts_layers') . $partsCollection['code'] . DS . $partsLayersCollection->getLayerCode() . DS;								
								Mage::helper('designersoftware/image')->createDirectory($path);							
								
								$path = Mage::helper('designersoftware/image')->createModuleImageDirectories('parts_layers') . $partsCollection['code'] . DS . $partsLayersCollection->getLayerCode() . DS . $textureCollection->getTextureCode() . DS;
								Mage::helper('designersoftware/image')->createDirectory($path);							
																
								$newFilename = $key . '.' . pathinfo($filename, PATHINFO_EXTENSION);
								/*$id = $this->getRequest()->getParam('id');
								if(empty($id)){
									$unquieName=uniqid('PL-') . rand(1, 100);
									$newFilename = $unquieName . '.' . pathinfo($filename, PATHINFO_EXTENSION);
								} else {
									$collection = $model->getCollection()->addFieldToFilter('parts_layers_id', $id)->getFirstItem();
									$newFilename = $collection->getFilename();
									if(!file_exists(Mage::helper('designersoftware/image')->_originalImageDirPath() . $newFilename) || empty($newFilename)){
										$unquieName=uniqid('PL-') . rand(1, 100);
										$newFilename = $unquieName . '.' . pathinfo($filename, PATHINFO_EXTENSION);
									}
								}*/
															
								$uploader->save($path, $newFilename);
								
							} catch (Exception $e) {
						  
							}						
						}
					endforeach;
				endif;
				
				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('designersoftware')->__('Parts Layers Texture was successfully saved'));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('designersoftware')->__('Unable to find parts layers texture to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('designersoftware/parts_layers_texture');
				 
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
        $designersoftwareIds = $this->getRequest()->getParam('parts_layers_texture');
        if(!is_array($designersoftwareIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select parts_layers_texture(s)'));
        } else {
            try {
                foreach ($designersoftwareIds as $designersoftwareId) {
                    $designersoftware = Mage::getModel('designersoftware/parts_layers_texture')->load($designersoftwareId);
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
        $designersoftwareIds = $this->getRequest()->getParam('parts_layers_texture');
        if(!is_array($designersoftwareIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select Texture(s)'));
        } else {
            try {
                foreach ($designersoftwareIds as $designersoftwareId) {
                    $designersoftware = Mage::getSingleton('designersoftware/parts_layers_texture')
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
        $fileName   = 'designersoftware_parts_layers_texture.csv';
        $content    = $this->getLayout()->createBlock('designersoftware/adminhtml_parts_layers_texture_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'designersoftware_parts_layers_texture.xml';
        $content    = $this->getLayout()->createBlock('designersoftware/adminhtml_parts_layers_texture_grid')
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
