<?php

class Inky_Designersoftware_Adminhtml_SizesController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('designersoftware/sizes')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Sizes Manager'), Mage::helper('adminhtml')->__('Sizes Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('designersoftware/sizes')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('sizes_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('designersoftware/sizes');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Sizes Manager'), Mage::helper('adminhtml')->__('Sizes Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Sizes News'), Mage::helper('adminhtml')->__('Sizes News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('designersoftware/adminhtml_sizes_edit'))
				->_addLeft($this->getLayout()->createBlock('designersoftware/adminhtml_sizes_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('designersoftware')->__('Sizes does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			
			//Update Atrributes Data for Multiple Records at a Time
			if(!empty($data['update_sizes_ids'])){
				//echo '<pre>';print_r($data);exit;	
				Mage::helper('designersoftware/sizes_updateattributes')->updateAttributes($data);
				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('designersoftware')->__('Size\'s was successfully Updated'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);
				$this->_redirect('*/*/');
				return;
			}
						
			// Set Default Color Id In case if no color has been selected 
			if(isset($data['default_color_id'])):
				$colorCollection = Mage::getModel('designersoftware/color')->getCollection()
								->addFieldToFilter('colorcode',$data['default_color_id'])
								->getFirstItem();
				$data['default_color_id'] = $colorCollection->getId();				
			endif;
			
			//echo '<pre>';print_r($data);exit;			
			// Save Multiple colors for a Size Image in Serialize format
			if(isset($data['links'])){
				$colors = Mage::helper('adminhtml/js')->decodeGridSerializedInput($data['links']['color']);
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
			}	
			
			
					
			
			$model = Mage::getModel('designersoftware/sizes');		
			
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
					
					$path = Mage::helper('designersoftware/image')->createModuleImageDirectories('','original');
							
					// Logic to generate Dynamic Name for Image and will use the same name every time when image will be entered
					$id = $this->getRequest()->getParam('id');
					if(empty($id)){
						$unquieName=uniqid('S-') . rand(1, 100);
						$newFilename = $unquieName . '.' . pathinfo($_FILES['filename']['name'], PATHINFO_EXTENSION);
					} else {
						$collection = $model->getCollection()->addFieldToFilter('sizes_id', $id)->getFirstItem();
						$newFilename = $collection->getFilename();
						if(!file_exists(Mage::helper('designersoftware/image')->_originalImageDirPath() . $newFilename) || empty($newFilename)){
							$unquieName=uniqid('S-') . rand(1, 100);
							$newFilename = $unquieName . '.' . pathinfo($_FILES['filename']['name'], PATHINFO_EXTENSION);
						}
					}
					
					$uploader->save( $path, $newFilename );
					
					//Thumb Images
					//Below will generate thumb images of entred sizes
					//it will create thumb and save images in path like - media/files/{{controllerName}}/{{Width}}X{{Height}}/{{filename}}
					// Given Parameters are just Range not actuall size to crop image															
					
					Mage::helper('designersoftware/image')->getThumbImage($newFilename,224,224); 
					Mage::helper('designersoftware/image')->getThumbImage($newFilename,100,100);
					Mage::helper('designersoftware/image')->getThumbImage($newFilename,51,53);
					
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
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('designersoftware')->__('Sizes was successfully saved'));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('designersoftware')->__('Unable to find sizes to save'));
        $this->_redirect('*/*/');
	}
    
    public function updateAttributesAction(){
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('designersoftware/sizes')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}
			
			Mage::register('sizes_data', $model);
			Mage::register('sizes_update_attributes', 'updateAttributes');

			$this->loadLayout();
			$this->_setActiveMenu('designersoftware/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Sizes'), Mage::helper('adminhtml')->__('Manage Sizes'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Sizes News'), Mage::helper('adminhtml')->__('Sizes News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('designersoftware/adminhtml_sizes_edit'))
				->_addLeft($this->getLayout()->createBlock('designersoftware/adminhtml_sizes_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('designersoftware')->__('Sizes does not exist'));
			$this->_redirect('*/*/');
		}
	 }
    
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('designersoftware/sizes');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Sizes was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $designersoftwareIds = $this->getRequest()->getParam('sizes');
        if(!is_array($designersoftwareIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select sizes(s)'));
        } else {
            try {
                foreach ($designersoftwareIds as $designersoftwareId) {
                    $designersoftware = Mage::getModel('designersoftware/sizes')->load($designersoftwareId);
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
        $designersoftwareIds = $this->getRequest()->getParam('sizes');
        if(!is_array($designersoftwareIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select sizes(s)'));
        } else {
            try {
                foreach ($designersoftwareIds as $designersoftwareId) {
                    $designersoftware = Mage::getSingleton('designersoftware/sizes')
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
    
    public function massSortingAction()
    {
		$designersoftwareIds = $this->getRequest()->getParam('sizes');
		//echo '<pre>';print_r($designersoftwareIds);exit;
        if(!is_array($designersoftwareIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Sorting not Updated'));
        } else {
            try {
                foreach ($designersoftwareIds as $designersoftwareId) {					
					$designersoftwareSortArray = explode(':',$designersoftwareId);
					$sizesId = $designersoftwareSortArray[0];
					$sortOrder = $designersoftwareSortArray[1];
					
                    $fonts = Mage::getSingleton('designersoftware/sizes')
                        ->load($sizesId)
                        ->setSortOrder($sortOrder)                        
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    //$this->__('Total of %d record(s) were successfully updated', count($designersoftwareIds) - 1)
                    $this->__('Sort Order Updated')
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'designersoftware_sizes.csv';
        $content    = $this->getLayout()->createBlock('designersoftware/adminhtml_sizes_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'designersoftware_sizes.xml';
        $content    = $this->getLayout()->createBlock('designersoftware/adminhtml_sizes_grid')
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
		$this->getLayout()->getBlock('sizes.color.grid')->setSizesColors($this->getRequest()->getPost('colors', null));               
        $this->renderLayout();
	}
	
	public function colorgridAction(){
        $this->loadLayout();
        $this->getLayout()->getBlock('sizes.color.grid')->setSizesColors($this->getRequest()->getPost('colors', null));
        $this->renderLayout();
    }
}
