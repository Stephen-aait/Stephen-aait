<?php
class NextBits_OrderAttachments_Block_Customer_Sales_Order_View_Fileattachments extends Mage_Core_Block_Template
{
	protected function _construct() {
		parent::_construct();
	}
	
	public function getOrder() {
		return Mage::registry('current_order');
	}
	
	public function getOrderViewBackUrl() {
		return Mage::getUrl('sales/order/view',array('order_id' => $this->getRequest()->getParam('order_id')));	
	}
	
	public function getVisibleOrderAttachments() {
        $orderAttachments = Mage::getModel('orderattachments/orderattachments')->getCollection()
			->addFieldToFilter('order_id',$this->getRequest()->getParam('order_id'))
			->addFieldToFilter('visible_customer_account', 1);
        $files = array();
       if (!empty($orderAttachments)) {
            foreach ($orderAttachments as $item) {
                $url   = Mage::helper('orderattachments')->getPath().$item->getFile();
               
                $files[] = array(
                    'id'                  => $item->getOrderAttachmentsId(),
                    'order_attachment_id' => $item->getOrderAttachmentsId(),
                    'order_id'            => $item->getOrderId(),
                    'url'                 => $url,
                    'file'                => $item->getFile(),
                    'file_name'           => $item->getFile(),
                    'comment'             => $item->getComment(),
                    'show'                => $item->getVisibleCustomerAccount(),
                    'created_on'          => $item->getCreatedOn(),
                    'updated_on'          => $item->getUpdatedOn(),
                );
            }
        }
        return $files;
    }
}
?>