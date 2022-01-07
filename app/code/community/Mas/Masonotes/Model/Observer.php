<?php
/**
 * Mas_Masonotes extension by Makarovsoft.com
 * 
 * @category   	Mas
 * @package		Mas_Masonotes
 * @copyright  	Copyright (c) 2014
 * @license		http://makarovsoft.com/license.txt
 * @author		makarovsoft.com
 */
/**
 * Observer
 *
 * @category	Mas
 * @package		Mas_Masonotes
 * 
 */
class Mas_Masonotes_Model_Observer extends Mage_Core_Helper_Abstract {
	
	public function handleNoteChanges($observer)
	{
		/* @var $note Mas_Masonotes_Model_Note */
		$note = $observer->getEvent()->getNote();
		
		$email = array(
			'store_id' => Mage::app()->getStore()->getId(),
			'sender_email' => Mage::getStoreConfig('masonotes/notifications/sender_email'),
			'sender_name' => Mage::getStoreConfig('masonotes/notifications/sender_name')
		);
		
		$orderId = $note->getOrderId();
		
		if ($orderId) {
			$order = Mage::getModel('sales/order')->load($orderId);
			$orderId = $order->getIncrementId();
		}
		
		if ($note->isObjectNew()) {
			$email['subject'] = $this->__('New note for order #%s was added', $orderId);
			$email['email_text'] = Mage::getStoreConfig('masonotes/general/mail_content'); //$this->__('Note is: ' . $note->getNote());
		} else {
			$email['subject'] = $this->__('Note for order #%s was updated', $orderId);
			$email['email_text'] = Mage::getStoreConfig('masonotes/general/mail_content'); //$this->__('Note is: ' . $note->getNote());
		}
		
		$admin = Mage::app()->getStore()->isAdmin();
		
		if ($admin) {
			if ($note->getStatus() == 1 && Mage::getStoreConfig('masonotes/notifications/enabled')) {
				$email['recepient'] = $order->getCustomerEmail();
				$email['email_text'] .= $this->__('<br /><br />') . $this->__('Click on link below to see it: ' . Mage::getUrl('sales/order/view', array('order_id' => $note->getOrderId())));
				Mage::helper('masonotes')->notify($email);
			}							
		} else {
			$email['email_text'] .= $this->__('<br /><br />') . $this->__('Click on link below to see it: ' . Mage::getUrl('adminhtml/sales_order/view', array('order_id' => $note->getOrderId())));
			if (Mage::getStoreConfig('masonotes/notifications/receive_notes') != '') {
				$emails = explode(',', Mage::getStoreConfig('masonotes/notifications/receive_notes'));
				
				foreach ($emails as $em) {
					$email['recepient'] = $em;
					Mage::helper('masonotes')->notify($email);			
				}
			}
		}
	}
	
	public function handleCustomerComment($observer){
		$orderComment = $this->_getRequest()->getPost('orderComment', false);
		$order = $observer->getEvent()->getOrder();
		
		if( !empty($orderComment) ){		
			$note = Mage::getModel('masonotes/note');
			$note->addData(
				array(
					'note' => $orderComment,
					'status' => 1,
					'customer_id' => $order->getCustomerId()
				)
			);
			$note->setStores(1);
			$note->setProductsData(array($order->getId()));
			$note->save();
		}
		return $this;
	}
	
	public function handleBlockOutput($observer) {
		/* @var $block Mage_Core_Block_Abstract */
        $block = $observer->getBlock();
        
        $transport = $observer->getTransport();
        $html = $transport->getHtml();
        
	 	if ($block instanceof Mage_Checkout_Block_Agreements) {
	 		
	 		if (strpos($html, 'masonotes') !== false) {
	 			return;
	 		}
			$insert = Mage::helper('masonotes/note')->getCheckoutComment();
			
			
        	if (!$block->getAgreements()) {
        		$html = '<form action="" id="checkout-agreements" onsubmit="return false;">' . $insert . '</form>' . $html;	
        	} else {
        		$searchFor = '<ol class="checkout-agreements">';
        		$html = str_replace($searchFor, $insert . $searchFor, $html);
        	}
        	
        	$transport->setHtml((string)$html);        	
        }
	}
}
