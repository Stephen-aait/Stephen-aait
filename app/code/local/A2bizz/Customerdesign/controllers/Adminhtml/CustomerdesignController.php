<?php
class A2bizz_Customerdesign_Adminhtml_CustomerdesignController extends Mage_Adminhtml_Controller_Action
{
	 protected function _initCustomer($idFieldName = 'id') {
        $customerId = (int) $this->getRequest()->getParam($idFieldName);
        $customerModel = Mage::getModel('customer/customer');
        if ($customerId) {
            $customerModel->load($customerId);
        }
        if (!$customerModel->getId()) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('customerdesign')->__('The customer not found'));
        }
        Mage::register('current_customer', $customerModel);
        return $this;
    }
    
	public function gridAction() {
        $this->_initCustomer();
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('customerdesign/adminhtml_customer_edit_tab_customerdesign_grid')->toHtml()
        );
    }
}

