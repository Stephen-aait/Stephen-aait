<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */
class Amasty_Orderattach_Adminhtml_Amorderattach_FieldController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_initAction()
             ->_addContent($this->getLayout()->createBlock('amorderattach/adminhtml_attachment'))
             ->renderLayout();
    }

    protected function _initAction()
    {
        // Check if is it bundle or standalone package
        $bundle = Mage::helper('core')->isModuleEnabled('Amasty_Ordermanager');
        $menu = $bundle ? 'sales/amomanager/amorderattach' : 'system/amorderattach';

        $this->loadLayout()
            ->_setActiveMenu($menu)
            ->_addBreadcrumb(Mage::helper('amorderattach')->__('System'), Mage::helper('sales')->__('System'))
            ->_addBreadcrumb(Mage::helper('amorderattach')->__('Manage Order Attachments'), Mage::helper('amorderattach')->__('Manage Order Attachments'));

        return $this;
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $this->_title($this->__('Order Attachments'))->_title($this->__('Edit Attachment Field'));

        $id    = $this->getRequest()->getParam('field_id');
        $field = Mage::getModel('amorderattach/field');
        if ($id) {
            $field->load($id);
            if (!$field->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('amorderattach')->__('This attachment field no longer exists.'));
                $this->_redirect('*/*/');

                return;
            }
        }

        // Set entered data if was error when we do save
        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $field->setData($data);
        }

        Mage::register('amorderattach_field', $field);

        $this->_initAction()
            ->_addContent($this->getLayout()->createBlock('amorderattach/adminhtml_field_edit'))
            ->_addLeft($this->getLayout()->createBlock('amorderattach/adminhtml_field_edit_tabs'))
            ->renderLayout();
    }

    public function validateAction()
    {

    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $id    = $this->getRequest()->getParam('field_id');
            $model = Mage::getModel('amorderattach/field');
            $allItems = $model->getCollection()->getItems();

            foreach ($allItems as $item) {
                if (!isset($data['code'])) {
                    break;
                }
                if ($item->getCode() == $data['code']) {
                    Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('adminhtml')->__('Field with "%s" alias alredy exist.', $data['code'])
                    );
                    $this->_redirect('*/*/new');
                    $this->_getSession()->setFormData($data);
                    return;
                }
            }

            if ($id) {
                $model->load($id);
            }

            if (isset($data['status_backend'])) {
                $data['status_backend'] = implode(',', $data['status_backend']);
            }
            if (isset($data['status_frontend'])) {
                $data['status_frontend'] = implode(',', $data['status_frontend']);
            }
            if (isset($data['apply_to_each_product']) && $data['apply_to_each_product'] == 1 && $model->getData('type')) {
                // add only if column doesn't exist
                if (!Mage::getModel('amorderattach/order_products')->hasField($model->getData('code'))) {
                    Mage::getModel('amorderattach/order_products')->addField($model->getData('type'), $model->getData('code'));
                    Mage::helper('amorderattach')->clearCache();
                }
            }
            $model->setData($data);

            $type = isset($data['hiddentype']) ? $data['hiddentype'] : $model->getData('type');

            if ('select' == $type) {
                $option = $model->getOptions();
                if ($option) {
                    $model->setOptions(implode(',', $option));
                }
            } else if ($type != 'link') {
                $model->setOptions('');
            }
            try {
                $model->save();
                $id = $model->getId();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('amorderattach')->__('The attachment field has been saved.'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                $this->_redirect('*/*/');

                return;

            } catch (Exception $e) {
                $this->_getSession()->addException($e, Mage::helper('amorderattach')->__('An error occurred while saving the attachment field: ') . $e->getMessage());
            }

            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', array('field_id' => $id));

            return;
        }
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('field_id')) {
            try {
                $model = Mage::getModel('amorderattach/field');
                $model->load($id);
                $model->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('amorderattach')->__('The attachment field has been deleted.'));
                $this->_redirect('*/*/');

                return;

            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('field_id' => $id));

                return;
            }
        }
    }
    
    public function massSortingAction()
    {
		$fieldIds = $this->getRequest()->getParam('field');
		//echo '<pre>';print_r($fieldIds);exit;
        if(!is_array($fieldIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Sorting not Updated'));
        } else {
            try {
                foreach ($fieldIds as $fieldId) {					
					$designersoftwareSortArray = explode(':',$fieldId);
					$categoryId = $designersoftwareSortArray[0];
					$sortOrder = $designersoftwareSortArray[1];
					
                    $fonts = Mage::getSingleton('amorderattach/field')
                        ->load($categoryId)
                        ->setSortOrder($sortOrder)                        
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    //$this->__('Total of %d record(s) were successfully updated', count($fieldIds) - 1)
                    $this->__('Sort Order Updated')
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    protected function _isAllowed()
    {
        $checkSystem = Mage::getSingleton('admin/session')->isAllowed('system/amorderattach');
        if ($checkSystem) {
            return $checkSystem;
        } else {
            return Mage::getSingleton('admin/session')->isAllowed('sales/amordermanagertoolkit/amorderattach');
        }
    }
}
