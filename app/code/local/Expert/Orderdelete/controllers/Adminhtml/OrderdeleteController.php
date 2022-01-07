<?php
/**
 * @author      Sharad Patel
 * @category    Sale
 * @package     Expert_Orderdelete
 * @copyright   Copyright (c) 2013 expertsofttechsolution.com 
 */

class Expert_Orderdelete_Adminhtml_OrderdeleteController extends Mage_Adminhtml_Controller_action
{
	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('orderdelete/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
     protected function _initOrder()
    {
        $id = $this->getRequest()->getParam('order_id');
        $order = Mage::getModel('sales/order')->load($id);

        if (!$order->getId()) {
            $this->_getSession()->addError($this->__('This Order is no longer exists.'));
            $this->_redirect('*/*/');
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
            return false;
        }
        Mage::register('sales_order', $order);
        Mage::register('current_order', $order);
        return $order;
    }
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}
	public function deleteAction() {
			$configValue = Mage::getStoreConfig('sales/orderdelete/enabled');
	if($configValue==1){

		if($order = $this->_initOrder()) {
			try {
     		    $order->delete()->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Order deleted'));
				$this->_redirectUrl(Mage::getBaseUrl().'admin/sales_order/index');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('order_ids')));
			}
		}
	}
		$this->_redirectUrl(Mage::getBaseUrl().'admin/sales_order/index');
	}
    public function massDeleteAction() {
        $orderdeleteIds = $this->getRequest()->getParam('order_ids');
		if(!is_array($orderdeleteIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($orderdeleteIds as $orderdeleteId) {
					Mage::getModel('sales/order')->load($orderdeleteId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($orderdeleteIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
		$this->_redirectUrl(Mage::getBaseUrl().'admin/sales_order/index');
    }
}