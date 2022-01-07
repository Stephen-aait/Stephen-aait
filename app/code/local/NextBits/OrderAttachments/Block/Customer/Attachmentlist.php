<?php
class NextBits_OrderAttachments_Block_Customer_Attachmentlist extends Mage_Core_Block_Template
{
	public function __construct(){
		parent::__construct();
		$customer = Mage::getSingleton('customer/session')->getCustomer();
			$customerId = $customer->getId();
		$orderattachments = Mage::getModel('orderattachments/orderattachments')->getCollection()
			->addFieldToFilter('customer_id',$customerId)
			->setOrder('created_on','DESC');
		$this->setOrderattachments($orderattachments);
	}
	
	protected function _prepareLayout(){
		parent::_prepareLayout();
		$pager = $this->getLayout()->createblock('page/html_pager','orderattachments.customer.list.pager')
			->setCollection($this->getOrderattachments());
		$this->setChild('pager',$pager);
		return $this;
	}
	
	public function getPagerHtml(){
		return $this->getChildHtml('pager');
	}
}
?>