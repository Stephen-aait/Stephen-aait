<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */
class Amasty_Orderattach_Adminhtml_Amorderattach_OrderController extends Mage_Adminhtml_Controller_Action
{
    private $itemValue;
    private $itemCode;

    public function saveAction()
    {
        $this->_initOrder();
        $productSubmit = strpos($this->getRequest()->getPost('field'), 'amProduct_') !== FALSE ? 1 : 0;
        // process product fields
        if ($productSubmit) {
            // extract all data from POST (stored value like "xyz_ProductId_FieldCode"
            $itemData  = explode('_', $this->getRequest()->getPost('field'), 3);
            $itemValue = $this->getRequest()->getPost('value');
            $itemCode  = $this->getRequest()->getPost('field');
            $itemId    = $itemData[1];
            $code      = $itemData[2];

            // load order_item and change it's column data
            $fieldModel = Mage::getModel('amorderattach/field')->load($code, 'code');
            $field     = Mage::getModel('amorderattach/order_products')->load($itemId, 'order_item_id');

            // triggering on save new row if OrderItemId is not set
            if (!$field->getOrderItemId()) {
                $field->setOrderItemId($itemId);
            }

            $field->setData($code, $itemValue);
            $field->save();
        } else {
            // process whole order fields
            $fieldModel = Mage::getModel('amorderattach/field')->load($this->getRequest()->getPost('field'), 'code');
            if ($fieldModel->getId()) {
                // load saved data from database and post
                $field     = Mage::getModel('amorderattach/order_field')->load(Mage::registry('current_order')->getId(), 'order_id');
                $code      = $this->getRequest()->getPost('field');
                $itemCode  = $this->getRequest()->getPost('field');
                $itemValue = $this->getRequest()->getPost('value');

                // triggering on save new row if OrderId is not set
                if (!$field->getOrderId()) {
                    $field->setOrderId(Mage::registry('current_order')->getId());
                }
            }
        }

        // save field data
        if ('date' == $code) {
            $itemValue = $itemValue ? date('Y-m-d', strtotime($itemValue)) : $itemValue;
        }
        $field->setData($itemCode, $itemValue);
        $field->save();

        // set sata for renderer and send response
        Mage::register('current_attachment_order_field', $field);
        $this->itemValue = $itemValue;
        $this->itemCode  = $itemCode;
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
        $productSubmit = strpos($this->getRequest()->getPost('field'), 'amProduct_') !== FALSE ? 1 : 0;
        // process product fields
        if ($productSubmit) {
            // extract all data from POST (stored value like "xyz_ProductId_FieldCode")
            $itemData = explode('_', $this->getRequest()->getPost('field'), 3);
            $itemId   = $itemData[1];
            $code     = $itemData[2];

            // load order_item and change it's column data
            $field = Mage::getModel('amorderattach/field')->load($code, 'code');
            if ($field->getId()) {
                // get product_field row with stored data
                $prodField = Mage::getModel('amorderattach/order_products')->load($itemId, 'order_item_id');

                // triggering on save new row if OrderItemId is not set
                if (!$prodField->getOrderItemId()) {
                    $prodField->setOrderItemId($itemId);
                }

                // uploading file
                if (isset($_FILES['to_upload']['error'])) {
                    try {
                        Mage::helper('amorderattach/upload')->uploadFile($prodField, $code);
                    } catch(Exception $e) {
                        $this->_getSession()->addException($e, Mage::helper('amorderattach')->__('An error occurred while saving the file: ') . $e->getMessage());
                    }
                    $prodField->save();
                    die('success');
                }
            }
            die('failed');
        } else {
            // process whole oder fields
            $field = $this->getRequest()->getPost('field');

            $fieldModel = Mage::getModel('amorderattach/field')->load($field, 'code');
            if ($fieldModel->getId()) {
                $orderField = Mage::getModel('amorderattach/order_field')->load(Mage::registry('current_order')->getId(), 'order_id');
                if (!$orderField->getOrderId()) {
                    $orderField->setOrderId(Mage::registry('current_order')->getId());
                }

                // uploading file
                if (isset($_FILES['to_upload']['error'])) {
                    try {
                        Mage::helper('amorderattach/upload')->uploadFile($orderField, $field);
                    } catch(Exception $e) {
                        $this->_getSession()->addaddException($e, Mage::helper('amorderattach')->__('An error occurred while saving the file: ') . $e->getMessage());
                    }
                    $orderField->save();
                    die('success');
                }
            }
            die('failed');
        }
    }

    public function downloadAction()
    {
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
        exit;
    }
    
	public function deleteallAction()
    {
		$this->_initOrder();
        $productSubmit = strpos($this->getRequest()->getPost('field'), 'amProduct_') !== FALSE ? 1 : 0;
        
        // process product fields
        if ($productSubmit) {
		} else {						
			// process whole order fields
            $value = '';
            $field = $this->getRequest()->getPost('field');
            $fieldModel = Mage::getModel('amorderattach/field')->load($field, 'code');
			
			//echo '<pre>';print_r($fieldModel);exit;
            if ($fieldModel->getId()) {
                $orderField = Mage::getModel('amorderattach/order_field')->load(Mage::registry('current_order')->getId(), 'order_id');
                //echo '<pre>';print_r($orderField);exit;
                if ($orderField->getOrderId()) {
					$filenameString = $orderField->getData($field);
					$filenameArray = explode(';',$filenameString);
					
					foreach($filenameArray as $filename){
						if (file_exists(Mage::helper('amorderattach/upload')->getUploadDir() . $fileName)) {
							@unlink(Mage::helper('amorderattach/upload')->getUploadDir() . $fileName);							
						}
					}
										                    
                    $orderField->setData($this->getRequest()->getPost('field'), $value);
                    $orderField->save();
                    Mage::register('current_attachment_order_field', $orderField); // required for renderer
                }
                
                if ($this->getRequest()->getParam('grid')) {
                    $type = $fieldModel->getType();

                    $block = $this->getLayout()
                        ->createBlock('adminhtml/template')
                        ->setData('field', $field)
                        ->setData('order_id', $orderField->getOrderId());

                    if ($type == 'file') {
                        $block->setTemplate('amorderattach/grid/file.phtml')
                            ->setData('value', $orderField->getData($field));
                    } else {
                        $block->setTemplate('amorderattach/grid/file_multiple.phtml')
                            ->setData('values', explode(';', trim($orderField->getData($field), " \t\n\r\0\x0B;")));
                    }
                    $this->getResponse()->setBody($block->toHtml());
                } else {
                    $this->itemValue = $value;
                    $this->itemCode  = $field;
                    $this->_sendResponse($fieldModel);
                }
            }
		}
	}
	
    public function deleteAction()
    {
        $this->_initOrder();
        $productSubmit = strpos($this->getRequest()->getPost('field'), 'amProduct_') !== FALSE ? 1 : 0;
        // process product fields
        if ($productSubmit) {
            // extract all data from POST (stored value like "xyz_ProductId_FieldCode")
            $itemData = explode('_', $this->getRequest()->getPost('field'), 3);
            $itemId   = $itemData[1];
            $code     = $itemData[2];
            $value    = '';

            // load order_item and change it's column data
            $fieldModel = Mage::getModel('amorderattach/field')->load($code, 'code');
            if ($fieldModel->getId()) {
                $prodField = Mage::getModel('amorderattach/order_products')->load($itemId, 'order_item_id');
                Mage::register('current_attachment_order_field', $prodField); // required for renderer
                if ($prodField->getOrderItemId()) {
                    $fileName = $this->getRequest()->getParam('file');
                    if (file_exists(Mage::helper('amorderattach/upload')->getUploadDir() . $fileName)) {
                        @unlink(Mage::helper('amorderattach/upload')->getUploadDir() . $fileName);
                    }
                    if ('file' == $this->getRequest()->getPost('type')) {
                        $value = '';
                    } elseif ('file_multiple' == $this->getRequest()->getPost('type')) {
                        $value = explode(';', $prodField->getData($code));
                        foreach ($value as $key => $val) {
                            if ($val == $fileName) {
                                unset($value[$key]);
                            }
                        }
                        $value = implode(';', $value);
                    }
                    $prodField->setData($code, $value);
                    $prodField->save();
                }
                if ($this->getRequest()->getParam('grid')) {
                    $type = $fieldModel->getType();

                    $block = $this->getLayout()
                        ->createBlock('adminhtml/template')
                        ->setData('itemId', $itemId)
                        ->setData('code', $code);

                    if ($type == 'file') {
                        $block->setTemplate('amorderattach/grid/file.phtml')
                              ->setData('value', $prodField->getData($code));
                    } else {
                        $block->setTemplate('amorderattach/grid/file_multiple.phtml')
                              ->setData('values', explode(';', trim($prodField->getData($code), " \t\n\r\0\x0B;")));
                    }
                    $this->getResponse()->setBody($block->toHtml());
                } else {
                    if ($fieldModel->getType() == 'file') {
                        $itemValue = $prodField->getData($code);
                    } else {
                        $itemValue = explode(';', trim($prodField->getData($code), " \t\n\r\0\x0B;"));
                    }
                    $this->itemValue = $itemValue;
                    $this->itemCode  = $fieldModel->getItemCode($itemId);
                    $this->_sendResponse($fieldModel);
                }
            }
        } else {
            // process whole order fields
            $value = '';
            $field = $this->getRequest()->getPost('field');
            $fieldModel = Mage::getModel('amorderattach/field')->load($field, 'code');

            if ($fieldModel->getId()) {
                $orderField = Mage::getModel('amorderattach/order_field')->load(Mage::registry('current_order')->getId(), 'order_id');
                if ($orderField->getOrderId()) {
                    $fileName = $this->getRequest()->getParam('file');
                    if (file_exists(Mage::helper('amorderattach/upload')->getUploadDir() . $fileName)) {
                        @unlink(Mage::helper('amorderattach/upload')->getUploadDir() . $fileName);
                    }
                    if ('file' == $this->getRequest()->getPost('type')) {
                        $value = '';
                    } elseif ('file_multiple' == $this->getRequest()->getPost('type')) {
                        $value = explode(';', $orderField->getData($field));
                        foreach ($value as $key => $val) {
                            if ($val == $fileName) {
                                unset($value[$key]);
                            }
                        }
                        $value = implode(';', $value);
                    }
                    $orderField->setData($this->getRequest()->getPost('field'), $value);
                    $orderField->save();
                    Mage::register('current_attachment_order_field', $orderField); // required for renderer
                }
                if ($this->getRequest()->getParam('grid')) {
                    $type = $fieldModel->getType();

                    $block = $this->getLayout()
                        ->createBlock('adminhtml/template')
                        ->setData('field', $field)
                        ->setData('order_id', $orderField->getOrderId());

                    if ($type == 'file') {
                        $block->setTemplate('amorderattach/grid/file.phtml')
                            ->setData('value', $orderField->getData($field));
                    } else {
                        $block->setTemplate('amorderattach/grid/file_multiple.phtml')
                            ->setData('values', explode(';', trim($orderField->getData($field), " \t\n\r\0\x0B;")));
                    }
                    $this->getResponse()->setBody($block->toHtml());
                } else {
                    $this->itemValue = $value;
                    $this->itemCode  = $field;
                    $this->_sendResponse($fieldModel);
                }
            }
        }
    }

    public function reloadAction()
    {
        $this->_initOrder();
        $productSubmit = strpos($this->getRequest()->getPost('field'), 'amProduct_') !== FALSE ? 1 : 0;

        // process product fields
        if ($productSubmit) {
            // extract all data from POST (stored value like "xyz_ProductId_FieldCode")
            $itemData = explode('_', $this->getRequest()->getPost('field'), 3);
            $itemId   = $itemData[1];
            $code     = $itemData[2];

            // load order_item and change it's column data
            $field = Mage::getModel('amorderattach/field')->load($code, 'code');
            if ($field->getId()) {
                $prodField = Mage::getModel('amorderattach/order_products')->load($itemId, 'order_item_id');
                Mage::register('current_attachment_order_field', $prodField); // required for renderer

                if ($this->getRequest()->getParam('grid')) {
                    $type = $field->getType();

                    $block = $this->getLayout()
                        ->createBlock('adminhtml/template')
                        ->setData('itemId', $itemId)
                        ->setData('code', $code);

                    if ($type == 'file') {
                        $block->setTemplate('amorderattach/grid/file.phtml')
                              ->setData('value', $prodField->getData($code));
                    } else {
                        $block->setTemplate('amorderattach/grid/file_multiple.phtml')
                              ->setData('values', explode(';', trim($prodField->getData($code), " \t\n\r\0\x0B;")));
                    }

                    $this->getResponse()->setBody($block->toHtml());
                } else
                    if ($field->getType() == 'file') {
                        $itemValue = $prodField->getData($code);
                    } else {
                        $itemValue = explode(';', trim($prodField->getData($code), " \t\n\r\0\x0B;"));
                    }
                $this->itemValue = $itemValue;
                $this->itemCode  = $field->getItemCode($itemId);
                $this->_sendResponse($field);
            }
        } else {
            // process whole order fields
            $field = $this->getRequest()->getPost('field');

            $fieldModel = Mage::getModel('amorderattach/field')->load($field, 'code');
            if ($fieldModel->getId()) {
                $orderField = Mage::getModel('amorderattach/order_field')->load(Mage::registry('current_order')->getId(), 'order_id');
                Mage::register('current_attachment_order_field', $orderField); // required for renderer

                if ($this->getRequest()->getParam('grid')) {
                    $type = $fieldModel->getType();

                    $block = $this->getLayout()
                        ->createBlock('adminhtml/template')
                        ->setData('field', $field)
                        ->setData('order_id', $orderField->getOrderId());

                    if ($type == 'file') {
                        $block->setTemplate('amorderattach/grid/file.phtml')
                            ->setData('value', $orderField->getData($field));
                    } else {
                        $block->setTemplate('amorderattach/grid/file_multiple.phtml')
                            ->setData('values', explode(';', trim($orderField->getData($field), " \t\n\r\0\x0B;")));
                    }

                    $this->getResponse()->setBody($block->toHtml());
                } else {
                    $this->itemValue = $orderField->getData($field);
                    $this->itemCode  = $field;
                    $this->_sendResponse($fieldModel);
                }
            }
        }
    }

    public function saveFieldAction()
    {
        if (!Mage::helper('amorderattach')->isAllowedEdit())
            return;

        $orderId = $this->getRequest()->getParam('order_id');
        $value   = $this->getRequest()->getParam('value');
        $fieldId = $this->getRequest()->getParam('field');

        $order = Mage::getModel('amorderattach/order_field')->load($orderId, 'order_id');
        $field = Mage::getModel('amorderattach/field')->load($fieldId, 'code');

        if (!$order->getId()) {
            $order->setOrderId($orderId);
        }

        if ($field->getId()) {
            $order->setData($field->getCode(), $value);
            $order->save();
            $success = 1;
        } else {
            $success = 0;
        }
        if ($field->getType() == 'text') {
            $value = str_replace("\n", "<br>\n", $value);
        }

        die(json_encode(array('success' => $success, 'value' => $value)));
    }

    protected function _initAction()
    {
        $this->loadLayout()
             ->_setActiveMenu('system/amorderattach')
             ->_addBreadcrumb(Mage::helper('amorderattach')->__('System'), Mage::helper('sales')->__('System'))
             ->_addBreadcrumb(Mage::helper('amorderattach')->__('Manage Order Attachments'), Mage::helper('amorderattach')->__('Manage Order Attachments'));

        return $this;
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed(
            'sales/order/actions/edit_memos'
        );
    }
}
