<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */
class Amasty_Orderattach_OrderController extends Mage_Core_Controller_Front_Action
{
    private $itemValue;
    private $itemCode;

    public function preDispatch()
    {
        parent::preDispatch();

        if (!Mage::getSingleton('customer/session')->authenticate($this)) {
            $this->setFlag('', 'no-dispatch', true);
        }
    }

    public function saveAction()
    {
        // prevent client editing not their own orders
        $field = '';
        $this->_initOrder();
        if (Mage::registry('current_order')->getCustomerId() != Mage::getSingleton('customer/session')->getCustomerId()) {
            $this->getResponse()->setBody('Error');
            exit;
        }

        /*
         * decide what form submitted: product field || order field
         * product fields have field names like:  "amProduct_%orderItemID%_%fieldID%"
         */
        $productSubmit = strpos($this->getRequest()->getPost('field'), 'amProduct_') !== FALSE ? 1 : 0;
        // process product fields
        if ($productSubmit) {
            // extract all data from POST (stored value like "xyz_orderItemID_FieldCode"
            $itemData = explode('_', $this->getRequest()->getPost('field'), 3);
            $itemId   = $itemData[1];
            $code     = $itemData[2];

            // load product item data
            $fieldModel = Mage::getModel('amorderattach/field')->load($code, 'code');
            $field      = Mage::getModel('amorderattach/order_products')->load($itemId, 'order_item_id');

            if ($fieldModel->getId()) {
                // triggering on save new row if OrderItemId is not set
                if (!$field->getOrderItemId()) {
                    $field->setOrderItemId($itemId);
                }

                $this->itemCode = $fieldModel->getItemCode($itemId);
            } else {
                die('Error on filed load: no such field found');
            }
        } else {
            $fieldModel = Mage::getModel('amorderattach/field')->load($this->getRequest()->getPost('field'), 'code');
            if ($fieldModel->getId()) {
                // load order item data
                $field = Mage::getModel('amorderattach/order_field')->load(Mage::registry('current_order')->getId(), 'order_id');
                $code  = $this->getRequest()->getPost('field');
                $this->itemCode = $code;

                // triggering on save new row if OrderItemId is not set
                if (!$field->getOrderId()) {
                    $field->setOrderId(Mage::registry('current_order')->getId());
                }
            } else {
                die('Error on filed load: no such field found');
            }
        }

        if ('date' == $code) {
            if ($this->getRequest()->getPost('value')) {
                $data          = $this->getRequest()->getPost();
                $valueToFilter = $this->_filterDates($data, array('value'));
                $itemValue     = date('Y-m-d', strtotime($valueToFilter['value']));
            } else {
                $itemValue = null;
            }
        } else {
            $itemValue = $this->getRequest()->getPost('value');
        }
        // set data
        $this->itemValue = $itemValue;
        $field->setData($code, $itemValue);

        // save if customer can edit
        if ('edit' == $fieldModel->getCustomerVisibility()) {
            $field->save();
        } else {
            die('Error on field save: no privileges for action');
        }


        Mage::register('current_attachment_order_field', $field); // required for renderer
        $this->_sendResponse($fieldModel);
    }

    protected function _initOrder()
    {
        $orderId = $this->getRequest()->getPost('order_id');
        $order   = Mage::getModel('sales/order')->load($orderId);
        Mage::register('current_order', $order);
    }

    protected function _sendResponse($fieldModel)
    {
        $this->getResponse()->setBody(
            $fieldModel->setType($fieldModel->getType())
                       ->getRenderer()
                       ->setItemValue($this->itemValue)
                       ->setItemCode($this->itemCode)
                       ->render()
        );
    }


    public function uploadAction()
    {
        $this->_initOrder();
        if (Mage::registry('current_order')->getCustomerId() != Mage::getSingleton('customer/session')->getCustomerId()) {
            $this->getResponse()->setBody('Error');
            exit;
        }

        /*
         * decide what form submitted: product field || order field
         * product fields have field names like:  "amProduct_%orderItemID%_%fieldID%"
         */
        $productSubmit = strpos($this->getRequest()->getPost('field'), 'amProduct_') !== FALSE ? 1 : 0;
			
        // process product fields
        if ($productSubmit) {			
            // extract all data from POST (stored value like "xyz_orderItemID_FieldCode")
            $itemData = explode('_', $this->getRequest()->getPost('field'), 3);
            $itemId   = $itemData[1];
            $code     = $itemData[2];

            // load order_item and change it's column data
            $fieldModel = Mage::getModel('amorderattach/field')->load($code, 'code');
            if ($fieldModel->getId()) {
                // get product_field row with stored data
                $prodField = Mage::getModel('amorderattach/order_products')->load($itemId, 'order_item_id');

                // triggering on save new row if OrderItemId is not set
                if (!$prodField->getOrderItemId()) {
                    $prodField->setOrderItemId($itemId);
                }

                // uploading file
                if (isset($_FILES['to_upload']['error'])) {
                    $this->uploadFile($prodField, $code);
                    $prodField->save();
                    die('Upload success');
                }
            }
            die('Failed to upload file');
        } else {
			
            $fieldModel = Mage::getModel('amorderattach/field')->load($this->getRequest()->getPost('field'), 'code');
            if ($fieldModel->getId() && 'edit' == $fieldModel->getCustomerVisibility()) {
                $orderField = Mage::getModel('amorderattach/order_field')->load(Mage::registry('current_order')->getId(), 'order_id');

                // triggering on save new row if OrderId is not set
                if (!$orderField->getOrderId()) {
                    $orderField->setOrderId(Mage::registry('current_order')->getId());
                }

                // uploading file
                if (isset($_FILES['to_upload']['error']) && UPLOAD_ERR_OK == $_FILES['to_upload']['error']) {
                    $this->uploadFile($orderField, $this->getRequest()->getPost('field'));
                    $orderField->save();
                    die('Upload success');
                }
            }
            die('Failed to upload file');
        }
    }

    protected function uploadFile(&$someField, $code)
    {
        $fileName = '';
        $multiple = is_array($_FILES['to_upload']['error']);
        for ($i = 0; $i < sizeof($_FILES['to_upload']['error']); $i++) {
            $error = $multiple ? $_FILES['to_upload']['error'][$i] : $_FILES['to_upload']['error'];

            if ($error == UPLOAD_ERR_OK) {
                try {
                    $fileName = $multiple ? $_FILES['to_upload']['name'][$i] : $_FILES['to_upload']['name'];
                    $fileName = Mage::helper('amorderattach/upload')->cleanFileName($fileName);
                    $uploader = new Varien_File_Uploader($multiple ? "to_upload[$i]" : 'to_upload');
                    $uploader->setFilesDispersion(false);
                    $fielDestination = Mage::helper('amorderattach/upload')->getUploadDir();
                    if (file_exists($fielDestination . $fileName)) {
                        $fileName = uniqid(date('ihs')) . $fileName;
                    }
                    $uploader->save($fielDestination, $fileName);
                } catch (Exception $e) {
                    Mage::getSingleton('customer/session')->addException($e, Mage::helper('amorderattach')->__('An error occurred while saving the file: ') . $e->getMessage());
                }
                
                $orgImagePath = Mage::helper('amorderattach/upload')->getUploadDir() . $fileName;
                $smallImagePath = Mage::helper('amorderattach/upload')->getSmallUploadDir() . $fileName;								
                
                if ('file' == $this->getRequest()->getPost('type')) // each new overwrites old one
                {
					$ext=pathinfo($fileName, PATHINFO_EXTENSION);
					switch($ext){
						case 'jpg':
						case 'jpeg':
						case 'png':
						case 'JPG':
							Mage::helper('designersoftware/image')->resizeImage($orgImagePath, $smallImagePath, 200,200);
					}
                    $someField->setData($code, $fileName);
                }
                if ('file_multiple' == $this->getRequest()->getPost('type')) {
                    $ext=pathinfo($fileName, PATHINFO_EXTENSION);
					switch($ext){
						case 'jpg':
						case 'jpeg':
						case 'png':
						case 'JPG':
							Mage::helper('designersoftware/image')->resizeImage($orgImagePath, $smallImagePath, 200,200);
					}					                    
                    
                    $fieldData   = explode(';', $someField->getData($code));
                    $fieldData[] = $fileName;
                    $fieldData   = implode(';', $fieldData);
                    $someField->setData($code, $fieldData);
                }
            }
        }
    }

    public function downloadAction()
    {
        if ($this->getRequest()->getParam('customer_id') == Mage::getSingleton('customer/session')->getCustomerId()) {
            $fileName = $this->getRequest()->getParam('file');
            $fileName = Mage::helper('amorderattach/upload')->cleanFileName($fileName);
            if (file_exists(Mage::helper('amorderattach/upload')->getUploadDir() . $fileName)) {
                header('Content-Disposition: attachment; filename="' . $fileName . '"');
                if (function_exists('mime_content_type')) {
                    header('Content-Type: ' . mime_content_type(Mage::helper('amorderattach/upload')->getUploadDir() . $fileName));
                } else if (class_exists('finfo')) {
                    $finfo    = new finfo(FILEINFO_MIME);
                    $mimetype = $finfo->file(Mage::helper('amorderattach/upload')->getUploadDir() . $fileName);
                    header('Content-Type: ' . $mimetype);
                }
                readfile(Mage::helper('amorderattach/upload')->getUploadDir() . $fileName);
            }
        }
        exit;
    }
    
    public function deleteallAction(){
		$this->_initOrder();
        if (Mage::registry('current_order')->getCustomerId() != Mage::getSingleton('customer/session')->getCustomerId()) {
            $this->getResponse()->setBody('Error');
            exit;
        }

		/*
         * decide what form submitted: product field || order field
         * product fields have field names like:  "amProduct_%orderItemID%_%fieldID%"
         */
        $productSubmit = strpos($this->getRequest()->getPost('field'), 'amProduct_') !== FALSE ? 1 : 0;
           
        // process product fields
        if ($productSubmit) {
            
        } else {
			$value='';
            $code       = $this->getRequest()->getPost('field');
            $fieldModel = Mage::getModel('amorderattach/field')->load($code, 'code');
            //echo '<pre>';print_r($fieldModel);exit;
            
            if ($fieldModel->getId() && 'edit' == $fieldModel->getCustomerVisibility()) {
				
                $orderField = Mage::getModel('amorderattach/order_field')->load(Mage::registry('current_order')->getId(), 'order_id');
                $filenameString = $orderField->getData($code);
				$filenameArray = explode(';',$filenameString);
				
				//echo '<pre>';print_r($filenameArray);exit;
				foreach($filenameArray as $filename){
					if (file_exists(Mage::helper('amorderattach/upload')->getUploadDir() . $fileName)) {
						@unlink(Mage::helper('amorderattach/upload')->getUploadDir() . $fileName);							
					}
				}
				$orderField->setData($code, $value);
				$orderField->save();
				
                // set values for render
                Mage::register('current_attachment_order_field', $orderField);
                $itemValue = $fieldModel->getType() == 'file' ? $orderField->getData($code) : explode(';', trim($orderField->getData($code), " \t\n\r\0\x0B;"));
            } else {
                die('Error on page reload');
            }
        }
        $this->itemValue = $itemValue;
        $this->itemCode  = $code;
        $this->_sendResponse($fieldModel);
	}

    public function deleteAction()
    {
        $this->_initOrder();
        if (Mage::registry('current_order')->getCustomerId() != Mage::getSingleton('customer/session')->getCustomerId()) {
            $this->getResponse()->setBody('Error');
            exit;
        }

        /*
         * decide what form submitted: product field || order field
         * product fields have field names like:  "amProduct_%orderItemID%_%fieldID%"
         */
        $productSubmit = strpos($this->getRequest()->getPost('field'), 'amProduct_') !== FALSE ? 1 : 0;

        // process product fields
        if ($productSubmit) {
            // extract all data from POST (stored value like "xyz_orderItemID_FieldCode")
            $itemData = explode('_', $this->getRequest()->getPost('field'), 3);
            $itemId   = $itemData[1];
            $code     = $itemData[2];

            // load order_item and change it's column data
            $fieldModel = Mage::getModel('amorderattach/field')->load($code, 'code');
            if ($fieldModel->getId()) {
                $prodField = Mage::getModel('amorderattach/order_products')->load($itemId, 'order_item_id');
                if ($prodField->getOrderItemId()) {
                    $this->deleteFile($prodField, $code);
                }
                // set values for render
                $itemValue = $fieldModel->getType() == 'file' ? $prodField->getData($code) : explode(';', trim($prodField->getData($code), " \t\n\r\0\x0B;"));
                Mage::register('current_attachment_order_field', $prodField);
            } else {
                die('Error on page reload');
            }
        } else {
            $code       = $this->getRequest()->getPost('field');
            $fieldModel = Mage::getModel('amorderattach/field')->load($code, 'code');
            if ($fieldModel->getId() && 'edit' == $fieldModel->getCustomerVisibility()) {
                $orderField = Mage::getModel('amorderattach/order_field')->load(Mage::registry('current_order')->getId(), 'order_id');
                if ($orderField->getOrderId()) {
                    $this->deleteFile($orderField, $code);
                }
                // set values for render
                Mage::register('current_attachment_order_field', $orderField);
                $itemValue = $fieldModel->getType() == 'file' ? $orderField->getData($code) : explode(';', trim($orderField->getData($code), " \t\n\r\0\x0B;"));
            } else {
                die('Error on page reload');
            }
        }
        $this->itemValue = $itemValue;
        $this->itemCode  = $code;
        $this->_sendResponse($fieldModel);
    }

    protected function deleteFile(&$filed, $code)
    {
        $fileName = $this->getRequest()->getParam('file');
        if (file_exists(Mage::helper('amorderattach/upload')->getUploadDir() . $fileName)) {
            @unlink(Mage::helper('amorderattach/upload')->getUploadDir() . $fileName);
        }
        if ('file' == $this->getRequest()->getPost('type')) {
            $value = '';
        } elseif ('file_multiple' == $this->getRequest()->getPost('type')) {
            $value = explode(';', $filed->getData($code));
            foreach ($value as $key => $val) {
                if ($val == $fileName) {
                    unset($value[$key]);
                }
            }
            $value = implode(';', $value);
        }
        $filed->setData($code, $value);
        $filed->save();
    }

    public function reloadAction()
    {
        $this->_initOrder();

        /*
         * decide what form submitted: product field || order field
         * product fields have field names like:  "amProduct_%orderItemID%_%fieldID%"
         */
        $productSubmit = strpos($this->getRequest()->getPost('field'), 'amProduct_') !== FALSE ? 1 : 0;

        // process product fields
        if ($productSubmit) {
            // extract all data from POST (stored value like "xyz_orderItemID_FieldCode")
            $itemData = explode('_', $this->getRequest()->getPost('field'), 3);
            $itemId   = $itemData[1];
            $code     = $itemData[2];

            // load order_item and change it's column data
            $fieldModel = Mage::getModel('amorderattach/field')->load($code, 'code');
            if ($fieldModel->getId()) {
                $prodField = Mage::getModel('amorderattach/order_products')->load($itemId, 'order_item_id');
                Mage::register('current_attachment_order_field', $prodField); // required for renderer
                $itemValue = $fieldModel->getType() == 'file' ? $itemValue = $prodField->getData($code) : $itemValue = explode(';', trim($prodField->getData($code), " \t\n\r\0\x0B;"));
                $code      = $this->getRequest()->getPost('field');
            } else {
                die('Reload error on filed load: no such field found');
            }
        } else {
            $code       = $this->getRequest()->getPost('field');
            $fieldModel = Mage::getModel('amorderattach/field')->load($code, 'code');
            if ($fieldModel->getId()) {
                $orderField = Mage::getModel('amorderattach/order_field')->load(Mage::registry('current_order')->getId(), 'order_id');
                Mage::register('current_attachment_order_field', $orderField); // required for renderer
                $itemValue = $orderField->getData($code);
            } else {
                die('Reload error on filed load: no such field found');
            }
        }
        $this->itemValue = $itemValue;
        $this->itemCode  = $code;
        $this->_sendResponse($fieldModel);
    }
}
