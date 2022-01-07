<?php
 /**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */

class Amasty_Orderattach_Adminhtml_Amorderattach_OrderAttachController extends Mage_Adminhtml_Controller_Action
{

    public function editAction()
    {
        $orderIds = Mage::app()->getRequest()->getParam('order_ids', array());
        Mage::register('order_ids', $orderIds);
        $this->loadLayout();
        $this->renderLayout();
    }

    public function saveAction()
    {
        $orderIds = Mage::app()->getRequest()->getParam('order_ids', '');

        if($orderIds) {
            $orderIds = explode(',', $orderIds);
        } else {
            $this->_getSession()
                 ->addError(
                     Mage::helper('amorderattach')
                         ->__(sprintf("Can't update orders(s). Order(s) is not selected."))
                 );
            return $this->_redirect('adminhtml/sales_order/index');
        }
        foreach ($orderIds as $orderId) {
            $orderField = Mage::getModel('amorderattach/order_field');
            $orderField->load($orderId, 'order_id');
            $orderField->setData('order_id', $orderId);
            $orderField->addData(Mage::app()->getRequest()->getParams());

            $orderField->save();
        }
        $this->_getSession()
             ->addSuccess(Mage::helper('amorderattach')
                 ->__(sprintf('Total of %d record(s) were updated.', sizeof($orderIds))));

        return $this->_redirect('adminhtml/sales_order/index');
    }

    public function uploadAction()
    {
        $result = array('errors' => array());
        $orderIds = Mage::app()->getRequest()->getParam('order_ids','');
        $orderIds = $orderIds ? explode(',',$orderIds) : array();

        $field = $this->getRequest()->getPost('field');
        $fieldModel = Mage::getModel('amorderattach/field')->load($field, 'code');
        if ($fieldModel->getId()) {
            foreach($orderIds as $orderId) {
                $orderField = Mage::getModel('amorderattach/order_field')
                    ->load($orderId, 'order_id');
                if (!$orderField->getOrderId()) {
                    $orderField->setOrderId($orderId);
                }

                // uploading file
                if (isset($_FILES['to_upload']['error'])) {
                    try {
                        Mage::helper('amorderattach/upload')->uploadFile($orderField, $field);
                        $orderField->save();
                    } catch (Exception $e) {
                        $result['errors'][] = $this->__('An error occurred while saving the file: %s', $e->getMessage());
                    }
                }
            }
        } else {
            $result['errors'] = $this->__("Can't find field by code '%s'", $field);
        }
        $this->getAnswer($result['errors']);
    }

    protected function getAnswer($errors, $content = '')
    {
        $this->getResponse()->setBody(
            Mage::helper('core')->jsonEncode(
                array(
                    'errors' => $errors,
                    'content' => $content,
                )
            )
        );
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed(
            'sales/order/actions/mass_edit_order_attach'
        );
    }

}
