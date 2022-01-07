<?php
/**
 * Manage store locator admin controller
 * 
 * @category    Clarion
 * @package     Clarion_Storelocator
 * @author      Clarion Magento Team
 */
class Clarion_Storelocator_Adminhtml_ManagestorelocatorController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Init actions
     *
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        $this->_title($this->__('Store Locator'));
        
        $this->loadLayout()
            ->_setActiveMenu('clarion_storelocator/clarion_manage_storelocator')
            ->_addBreadcrumb(Mage::helper('clarion_storelocator')->__('Store Locator'), Mage::helper('clarion_storelocator')->__('Store Locator'))
        ;
        return $this;
    }
    
    /**
     * Index action method
     */
    public function indexAction() 
    {
        $this->_initAction();
        $this->renderLayout();
    }
    
    /**
     * Used for Ajax Based Grid
     *
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('clarion_storelocator/adminhtml_storelocator_grid')->toHtml()
        );
    }
    
    /**
     * Export csv
     *
     */
    public function exportCsvAction()
    {
        $fileName   = 'storelocator.csv';
        $content    = $this->getLayout()->createBlock('clarion_storelocator/adminhtml_storelocator_grid')
            ->getCsv();
 
        $this->_sendUploadResponse($fileName, $content);
    }
    
    /**
     * Export xml
     *
     */
    public function exportXmlAction()
    {
        $fileName   = 'storelocator.xml';
        $content    = $this->getLayout()->createBlock('clarion_storelocator/adminhtml_storelocator_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }
    
    /**
     * Export csv and xml
     *
     */
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
    
    /**
     * Multiple store deletion
     *
     */
    public function massDeleteAction()
    {
        //Get store ids from selected checkbox
        $storelocatorIds = $this->getRequest()->getParam('storelocatorIds');
        
        if (!is_array($storelocatorIds)) {
             Mage::getSingleton('adminhtml/session')->addError($this->__('Please select store(s).'));
        } else {
            if (!empty($storelocatorIds)) {
                try {
                    foreach ($storelocatorIds as $storelocatorId) {
                        $storelocator = Mage::getSingleton('clarion_storelocator/storelocator')->load($storelocatorId);
                        //delete image file
                        if($storelocator->getStoreLogo()) {
                            $oldFileTargetPath = Mage::getBaseDir('media') . DS . 'clarion_storelocator' . DS . $storelocator->getStoreLogo();
                            $this->deleteFile($oldFileTargetPath);
                        }
                        //delete record
                        $storelocator->delete();
                    }
                     Mage::getSingleton('adminhtml/session')->addSuccess(
                        $this->__('Total of %d record(s) have been deleted.', count($storelocatorIds))
                    );
                } catch (Exception $e) {
                     Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                }
            }
        }
        $this->_redirect('*/*/');
    }
    
     /**
     * Multiple store status update
     *
     */
    public function massStatusAction()
    {
        //Get store ids from selected checkbox
        $storelocatorIds = $this->getRequest()->getParam('storelocatorIds');
        
        if (!is_array($storelocatorIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select store(s)'));
        } else {
            try {
                foreach ($storelocatorIds as $storelocatorId) {
                    Mage::getSingleton('clarion_storelocator/storelocator')
                    ->load($storelocatorId)
                    ->setStatus($this->getRequest()->getParam('status'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($storelocatorIds))
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
    
    /**
     * Create new store action
     */
    public function newAction()
    {
        // the same form is used to create and edit
        $this->_forward('edit');
    }
    
    /**
     * Edit store action
     */
    public function editAction()
    {
        $this->_title($this->__('Store Locator'))
             ->_title($this->__('Manage Store Locator'));

        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('storelocator_id');
        $model = Mage::getModel('clarion_storelocator/storelocator');

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (! $model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('clarion_storelocator')->__('This store no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }

        $this->_title($model->getId() ? $model->getName() : $this->__('New Store'));

        // 3. Set entered data if was error when we do save
        $data = Mage::getSingleton('adminhtml/session')->getStorelocatorData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        // 4. Register model to use later in blocks
        Mage::register('storelocator_data', $model);

        // 5. Build edit form
        $this->_initAction()
            ->_addBreadcrumb(
                $id ? Mage::helper('clarion_storelocator')->__('Edit Store')
                    : Mage::helper('clarion_storelocator')->__('New Store'),
                $id ? Mage::helper('clarion_storelocator')->__('Edit Store')
                    : Mage::helper('clarion_storelocator')->__('New Store'));
        
         $this->_addContent($this->getLayout()->createBlock('clarion_storelocator/adminhtml_storelocator_edit'))
                 ->_addLeft($this->getLayout()->createBlock('clarion_storelocator/adminhtml_storelocator_edit_tabs'));

        $this->renderLayout();
    }
    
    /**
     * Upload new file
     *
     * @param string $targetPath Target directory
     * @throws Mage_Core_Exception
     * @return array File info Array
     */
    public function uploadFile($targetPath)
    {
        try {      
            $imageName = $_FILES['store_logo']['name']; //file name     
            $uploader = new Mage_Core_Model_File_Uploader('store_logo'); //load class
            $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));//Allowed extension for file
            $uploader->setAllowCreateFolders(true); //for creating the directory if not exists
            $uploader->setAllowRenameFiles(true); //if true, uploaded file's name will be changed, if file with the same name already exists directory.
            $uploader->setFilesDispersion(false);

            $result = $uploader->save($targetPath, $imageName); //save the file on the specified path

            if (!$result) {
                 Mage::throwException( Mage::helper('clarion_storelocator')->__('Cannot upload file.') );
            }
            return $result;
        } catch (Exception $e) {
            $this->_getSession()->addException($e, $e->getMessage());
        }
    }
    
    /**
     * Delete file
     *
     * @param string $targetPath File path to be deleted
     * @return boolean
     */
    public function deleteFile($targetPath)
    {
        $result = false;
        $io = new Varien_Io_File();
        
        if($io->fileExists($targetPath)) {
            $result = $io->rm($targetPath);
        }
        return $result;
    }
    
    /**
     * Save action
     */
    public function saveAction()
    {
        // check if data sent
        if ($data = $this->getRequest()->getPost()) {

            $id = $this->getRequest()->getParam('storelocator_id');
            $model = Mage::getModel('clarion_storelocator/storelocator');
            $session    = Mage::getSingleton('adminhtml/session');
            /* @var $session Mage_Core_Model_Session */
            
            if ($id) {
                $model->load($id);
                 if (!$model->getId()) {
                    $session->addError(Mage::helper('clarion_storelocator')->__('This store no longer exists.'));
                    $this->_redirect('*/*/');
                    return;
                 }
            }
            
            // validate form
            $validate = $this->validate($data);
            if($validate !== true){
                if (is_array($validate)) {
                    foreach ($validate as $errorMessage) {
                        $session->addError($errorMessage);
                    }
                }
                else {
                    $session->addError($this->__('Unable to add/edit store.'));
                }
                $session->setStorelocatorData($data);
                $this->_redirect('*/*/edit', array('storelocator_id' => $this->getRequest()->getParam('storelocator_id')));
                return;
            }
            
            // Store logo Image upload
            if(isset($_FILES['store_logo']['name']) && $_FILES['store_logo']['name'] != '') {
                $targetPath = Mage::getBaseDir('media') . DS . 'clarion_storelocator' . DS; //desitnation directory  
                //upload file
                $result = $this->uploadFile($targetPath);
                
                if($result['file']) {
                    $data['store_logo'] = $result['file'];
                }
                
                //delete old file
                if ($id) {
                    $oldFileTargetPath = Mage::getBaseDir('media') . DS . 'clarion_storelocator' . DS . $model->getStoreLogo();
                    $this->deleteFile($oldFileTargetPath);
                }
            } else {  
                //if you are using an image field type, (image is set in addField)
                if(isset($data['store_logo']['delete']) && $data['store_logo']['delete'] == 1) {
                    
                    //delete old file
                    if ($id) {
                        $oldFileTargetPath = Mage::getBaseDir('media') . DS . 'clarion_storelocator' . DS . $model->getStoreLogo();
                        $this->deleteFile($oldFileTargetPath);
                    }
                    
                    $data['store_logo'] = '';
                } else {
                    unset($data['store_logo']);
                }
            }
            
            $currentTimestamp = Mage::getModel('core/date')->timestamp(time());
            if ($id) {
                $data['updated_at'] = $currentTimestamp; 
             }else {
                $data['created_at'] = $currentTimestamp; 
             }
             
            // init model and set data
            $model->setData($data);
            
            // try to save it
            try {
                // save the data
                $model->save();
                // display success message
                $session->addSuccess(Mage::helper('clarion_storelocator')->__('The store has been saved.'));
                // clear previously saved data from session
                $session->setStorelocatorData(false);

                // check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('storelocator_id' => $model->getId(), '_current'=>true));
                    return;
                }
                // go to grid
                $this->_redirect('*/*/');
                return;

            } catch (Mage_Core_Exception $e) {
                $session->addError($e->getMessage());
            }
            catch (Exception $e) {
                $session->addException($e,
                Mage::helper('clarion_storelocator')->__('An error occurred while saving the store.'));
            }
            $session->setStorelocatorData($data);
            $this->_redirect('*/*/edit', array('storelocator_id' => $this->getRequest()->getParam('storelocator_id')));
            return;
        }
        $this->_redirect('*/*/');
    }
    
    public function validate($data)
    {
        $errors = array();
        
        $storelocator = Mage::getModel('clarion_storelocator/storelocator');
        $storeName = isset($data['name']) ? $data['name'] : '';
        $storelocatorId = isset($data['storelocator_id']) ? $data['storelocator_id'] : '';
        
        if($storelocator->storeExists($storeName, $storelocatorId)) {
            $errors[] = Mage::helper('clarion_storelocator')->__('Store name already exists');
        }
        
        //store image file validation
        if ($_FILES['store_logo']['error'] == 0) {
            $allowedExtensions = array('gif', 'jpeg', 'jpg', 'png');
            $fileName = $_FILES['store_logo']['name'];
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            if($fileExtension){
                if(!in_array($fileExtension, $allowedExtensions)) {
                    $errors[] = Mage::helper('clarion_storelocator')->__('Allowed image extension are jpg, jpeg, gif, png');
                }
            }
        }
        
        if (empty($errors)) {
            return true;
        }
        return $errors;
    }
    
    /**
     * Delete action
     */
    public function deleteAction()
    {
        // check if we know what should be deleted
        if ($id = $this->getRequest()->getParam('storelocator_id')) {
            try {
                // init model and delete
                $model = Mage::getModel('clarion_storelocator/storelocator');
                $model->load($id);
                //delete image file
                if ($model->getStoreLogo()) {
                    $oldFileTargetPath = Mage::getBaseDir('media') . DS . 'clarion_storelocator' . DS . $model->getStoreLogo();
                    $this->deleteFile($oldFileTargetPath);
                }
                //delete store
                $model->delete();
                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('clarion_storelocator')->__('The store has been deleted.'));
                // go to grid
                $this->_redirect('*/*/');
                return;

            } catch (Exception $e) {
                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                // go back to edit form
                $this->_redirect('*/*/edit', array('storelocator_id' => $id));
                return;
            }
        }
        // display error message
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('clarion_storelocator')->__('Unable to find a store to delete.'));
        // go to grid
        $this->_redirect('*/*/');
    }
    
    /**
     * Check the permission to run it
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        switch ($this->getRequest()->getActionName()) {
            case 'new':
            case 'save':
                return Mage::getSingleton('admin/session')->isAllowed('clarion_storelocator/clarion_manage_storelocator/save');
                break;
            case 'massDelete':
                return Mage::getSingleton('admin/session')->isAllowed('clarion_storelocator/clarion_manage_storelocator/delete');
                break;
            case 'delete':
                return Mage::getSingleton('admin/session')->isAllowed('clarion_storelocator/clarion_manage_storelocator/delete');
                break;
            
             case 'exportCsv':
              case 'exportXml':
                return Mage::getSingleton('admin/session')->isAllowed('clarion_storelocator/import_export/export');
                break;
            default:
                return Mage::getSingleton('admin/session')->isAllowed('clarion_storelocator/clarion_manage_storelocator');
                break;
        }
    }
    
    /**
     * Flush stores Images Cache action
     */
    public function flushAction()
    {
        if (Mage::helper('clarion_storelocator/image')->flushImagesCache()) {
            $this->_getSession()->addSuccess('Cache successfully flushed');
        } else {
            $this->_getSession()->addError('There was error during flushing cache');
        }
        $this->_forward('index');
    }
}