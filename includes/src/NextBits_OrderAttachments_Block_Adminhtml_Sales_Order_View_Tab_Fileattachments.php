<?php
class NextBits_OrderAttachments_Block_Adminhtml_Sales_Order_View_Tab_Fileattachments
    extends Mage_Adminhtml_Block_Template implements Mage_Adminhtml_Block_Widget_Tab_Interface 
{    
    public function _construct() {
        parent::_construct();
        $this->setTemplate('nborderattachments/sales/order/view/tab/fileattachments.phtml'); 
    }
	
	public function getTabLabel() {
        return $this->__('File Attachments');
    }

    public function getTabTitle() {
        return $this->__('File Attachments');
    }

    public function canShowTab() {
        return true;
    }

    public function isHidden() {
        return false;
    }

    public function getOrder() {
        return Mage::registry('current_order');
    }

	public function getOrderAttachments() {
        $orderAttachments = Mage::getModel('orderattachments/orderattachments')->getCollection()
            ->addFieldToFilter('order_id', $this->getRequest()->getParam('order_id'));

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