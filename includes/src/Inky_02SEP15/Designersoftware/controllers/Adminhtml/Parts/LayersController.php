<?php

class Inky_Designersoftware_Adminhtml_Parts_LayersController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('designersoftware/parts_layers')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Parts Layers Manager'), Mage::helper('adminhtml')->__('Parts Layers Manager'));
		
		return $this;
	}   
 
	public function indexAction() {		
		$this->_initAction()
			->renderLayout();
	}	

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('designersoftware/parts_layers')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('parts_layers_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('designersoftware/parts_layers');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Parts Layers Manager'), Mage::helper('adminhtml')->__('Parts Layers Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Parts Layers News'), Mage::helper('adminhtml')->__('Parts Layers News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('designersoftware/adminhtml_parts_layers_edit'))
				->_addLeft($this->getLayout()->createBlock('designersoftware/adminhtml_parts_layers_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('designersoftware')->__('Parts Layers does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
	
    public function checkDuplicateCode($partsStyleId, $code){
		$model = Mage::getModel('designersoftware/parts_layers')->getCollection()
							->addFieldToFilter('parts_style_id',$partsStyleId)
							->addFieldToFilter('layer_code',$code)
							->getData();
		if(count($model)>0):
			return false;
		else:
			return true;
		endif;
	}
	
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			
			// ***************** STORE DATA *****************
			$storeData = $data;
			$data['title'] = $storeData['title'][1];
			// ***************** STORE DATA *********END********
						
			//Update Atrributes Data for Multiple Records at a Time
			if(!empty($data['update_parts_layers_ids'])){
				//echo '<pre>';print_r($data);exit;	
				Mage::helper('designersoftware/parts_layers_updateattributes')->updateAttributes($data);
				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('designersoftware')->__('Parts Layers\'s was successfully Updated'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);
				$this->_redirect('*/*/');
				return;
			}
			
			
			//echo '<pre>';print_r($data);exit;
			if ($this->getRequest()->getParam('id')) {
				$partsLayersCollection = Mage::getModel('designersoftware/parts_layers')->getCollection()
										->addFieldToFilter('parts_layers_id', $this->getRequest()->getParam('id'))
										->addFieldToFilter('status',1)
										->getFirstItem();
				
				$data['parts_style_id'] = $partsLayersCollection->getPartsStyleId();
				//$data['parts_style_id'] = $partsLayersCollection->getPartsStyleId();
			}
			
			
			if(!$this->checkDuplicateCode($data['parts_style_id'], $data['layer_code'])):
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('designersoftware')->__('Duplicate Layer Code, Please enter unique value.'));
				
				if ($this->getRequest()->getParam('id')) {
					//$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
					//return;
				} else {
					$this->_redirect('*/*/new');
					return;
				}
				
			endif;			

			if(isset($data['default_color_id'])):
				$colorCollection = Mage::getModel('designersoftware/color')->getCollection()
								->addFieldToFilter('colorcode',$data['default_color_id'])
								->getFirstItem();
				$data['default_color_id'] = $colorCollection->getId();				
			endif;
			
			if(isset($data['links'])){
				$colors = Mage::helper('adminhtml/js')->decodeGridSerializedInput($data['links']['color']);
				//echo '<pre>';print_r($colors);exit;	
				if(!empty($colors)):
					$colorIdArray = array_keys($colors);
					if(!isset($data['default_color_id']) || !in_array($data['default_color_id'],$colorIdArray)):
						$data['default_color_id'] 	= 	$colorIdArray[0];
					endif;
					$data['color_price'] 		= serialize($data['color_price']);
					$data['color_ids'] 			= serialize(array_keys($colors));
				else:
					$data['color_ids'] 			= '';
					$data['default_color_id'] 	= '';
				endif;
				
				$texture = Mage::helper('adminhtml/js')->decodeGridSerializedInput($data['links']['texture']);
				if(!empty($colors)):
					$data['texture_ids'] = serialize(array_keys($texture));
				else:
					$data['texture_ids'] = '';
				endif;		
			}
			
			
			//echo '<pre>';print_r($data);exit;	
			
			
			// Convert According to Database
			//$parts_type_id = $data['parts_type_id'][$data['parts_id']];  
			//$data['parts_type_id'] = $parts_type_id;
			
			$model = Mage::getModel('designersoftware/parts_layers');

			//this way the name is saved in DB
			//$data['filename'] = $newFilename;			
			//echo '<pre>';print_r($data);exit;
	  			
					
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
				
				//********************** SAVE STORE DATA *********************************
				
				$storeData['parts_layers_id'] = $model->getId();					
				Mage::helper('designersoftware/store_parts_layers')->setDataById($storeData);	
					
				//********************** SAVE STORE DATA ******* END *********************
				
				/*$layersId = $this->getRequest()->getParam('id');
				if(empty($layersId)){
					 $layerCode = Mage::helper('designersoftware/parts_layers')->getCode($data, $model->getId());
					 $model->setLayerCode($layerCode);
					 $model->save();
				}*/
				$partsLayersCollection = Mage::getModel('designersoftware/parts_layers')->getCollection()->addFieldToFilter('parts_layers_id', $model->getId())->addFieldToFilter('status',1)->getFirstItem();
				$partsStyleCollection = Mage::getModel('designersoftware/parts_style')->getCollection()->addFieldToFilter('parts_style_id', $data['parts_style_id'])->addFieldToFilter('status',1)->getFirstItem()->getData();
				//echo '<pre>';print_r($partsCollection['code']	);exit;
				
				// Remove Images Action 							
				$midPath = $partsStyleCollection['code'] . DS  .$partsLayersCollection->getLayerCode();
				Mage::helper('designersoftware/parts_layers')->removeLayers($data['delImage'], $midPath);
				
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
								
								$path = Mage::helper('designersoftware/image')->createModuleImageDirectories() . $partsStyleCollection['code'] . DS;
								Mage::helper('designersoftware/image')->createDirectory($path);							
								
								$path = Mage::helper('designersoftware/image')->createModuleImageDirectories() . $partsStyleCollection['code'] . DS . $partsLayersCollection->getLayerCode() . DS;								
								Mage::helper('designersoftware/image')->createDirectory($path);							
								
								$orgPath = Mage::helper('designersoftware/image')->createModuleImageDirectories() . $partsStyleCollection['code'] . DS . $partsLayersCollection->getLayerCode() . DS . 'original' . DS;								
								Mage::helper('designersoftware/image')->createDirectory($orgPath);							
																						
								$newFilename = $key . '.' . pathinfo($filename, PATHINFO_EXTENSION);								
								
								$uploader->save($orgPath, $newFilename);
								
								list($oWidth, $oHeight) = @(getimagesize($orgPath.$newFilename));
								
								$this->cropLayerImage($data['parts_style_id'],290,540,$oWidth,$oHeight,$orgPath,$path,$newFilename);
								$this->cropLayerImage($data['parts_style_id'],235,440,$oWidth,$oHeight,$orgPath,$path,$newFilename);
								$this->cropLayerImage($data['parts_style_id'],20,90,$oWidth,$oHeight,$orgPath,$path,$newFilename);								
								
							} catch (Exception $e) {
						  
							}						
						}
					endforeach;
				endif;
				
				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('designersoftware')->__('Parts Layers was successfully saved'));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('designersoftware')->__('Unable to find parts layers to save'));
        $this->_redirect('*/*/');
	}
	
	
	public function cropLayerImage($partsStyleId, $reqWidth, $reqHeight, $oWidth, $oHeight, $orgPath, $path, $newFilename){
		
		$canvas_image_width 	= $reqWidth; 
		$canvas_image_height 	= $reqHeight;
		
		$arr = array('4','5','6');
			
		if(in_array($partsStyleId,$arr)): //For pocket
		
			list($bodyOrigWidth, $bodyOrigHeight) = @(getimagesize(Mage::getBaseDir('media') . DS . 'inky/parts_layers/BSE/LAYER1/original/000.png'));																											
			list($bodyRatioWidth, $bodyRatioHeight) = @(getimagesize(Mage::getBaseDir('media') . DS . 'inky/parts_layers/BSE/LAYER1/'.$reqWidth.'x'.$reqHeight.'/000.png'));									
			
			$fixW=$bodyRatioWidth*$oWidth/$bodyOrigWidth;
			$fixH=$bodyRatioHeight*$oHeight/$bodyOrigHeight;
		
			Mage::helper('designersoftware/image')->resizeImage($orgPath.$newFilename, $path.$canvas_image_width.'x'. $canvas_image_height . DS .$newFilename, $fixW, $fixH);
		else:
			// Original image height divide by required height of MAin Body Bag
			// Image whole size Parameteres
			if($oWidth>$oHeight):
				$ratio = $oWidth/$canvas_image_width; 
			else:
				$ratio = $oHeight/$canvas_image_height;
			endif;						
			
			$width=$oWidth/$ratio;
			$height=$oHeight/$ratio;
			
			Mage::helper('designersoftware/image')->resizeImage($orgPath.$newFilename, $path.$canvas_image_width .'x'. $canvas_image_height . DS . $newFilename, $width, $height);																																		
		endif;	
	}
	
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('designersoftware/parts_layers');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Parts Layers was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $designersoftwareIds = $this->getRequest()->getParam('parts_layers');
        if(!is_array($designersoftwareIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select parts layers(s)'));
        } else {
            try {
                foreach ($designersoftwareIds as $designersoftwareId) {
                    $designersoftware = Mage::getModel('designersoftware/parts_layers')->load($designersoftwareId);
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
        $designersoftwareIds = $this->getRequest()->getParam('parts_layers');
        if(!is_array($designersoftwareIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select parts layers(s)'));
        } else {
            try {
                foreach ($designersoftwareIds as $designersoftwareId) {
                    $designersoftware = Mage::getSingleton('designersoftware/parts_layers')
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
        $fileName   = 'designersoftware_parts_layers.csv';
        $content    = $this->getLayout()->createBlock('designersoftware/adminhtml_parts_layers_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'designersoftware_parts_layers.xml';
        $content    = $this->getLayout()->createBlock('designersoftware/adminhtml_parts_layers_grid')
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
	
	public function colorAction() {		
		$this->loadLayout();
		//echo '<pre>'.$this->getLayout()->getNode()->asXml();exit;
		$this->getLayout()->getBlock('layers.color.grid')->setLayersColors($this->getRequest()->getPost('colors', null));        
        
        $this->renderLayout();
	}
	
	public function colorgridAction(){
        $this->loadLayout();
        $this->getLayout()->getBlock('layers.color.grid')->setLayersColors($this->getRequest()->getPost('colors', null));
        $this->renderLayout();
    }
    
    
    public function textureAction() {		
		$this->loadLayout();
		//echo '<pre>'.$this->getLayout()->getNode()->asXml();exit;
		$this->getLayout()->getBlock('layers.texture.grid')->setLayersTextures($this->getRequest()->getPost('textures', null));        
        
        $this->renderLayout();
	}
	
	public function texturegridAction(){
        $this->loadLayout();
        $this->getLayout()->getBlock('layers.texture.grid')->setLayersTextures($this->getRequest()->getPost('textures', null));
        $this->renderLayout();
    }
    
    public function updateAttributesAction(){				
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('designersoftware/parts_layers')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}
			
			Mage::register('parts_layers_data', $model);
			Mage::register('parts_layers_update_attributes', 'updateAttributes');

			$this->loadLayout();
			$this->_setActiveMenu('designersoftware/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Color'), Mage::helper('adminhtml')->__('Manage Parts Layers'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Color News'), Mage::helper('adminhtml')->__('Parts Layers News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('designersoftware/adminhtml_parts_layers_edit'))
				->_addLeft($this->getLayout()->createBlock('designersoftware/adminhtml_parts_layers_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('designersoftware')->__('Parts Layers does not exist'));
			$this->_redirect('*/*/');
		}
	 }
}
