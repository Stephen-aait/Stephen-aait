<?php
class NextBits_OrderAttachments_Helper_Data extends Mage_Core_Helper_Abstract
{
	const XML_PATH_ORDERATTACHMENTS_EMAIL_TEMPLATE  = 'orderattachments/general/custom_email_template';
	const XML_PATH_ORDERATTACHMENTS_ENABLED         = 'orderattachments/general/enabled_email';
	public function getConfiguration() {
        $pathGeneral  = 'orderattachments/general/';
        $config = array(
			'attachment_dir'                    => Mage::getStoreConfig($pathGeneral . 'attachment_dir'),
			'allowed_extension'                 => Mage::getStoreConfig($pathGeneral . 'allowed_extensions'),
			'num_allowed_attachment'            => Mage::getStoreConfig($pathGeneral . 'num_allowed_attachment'),
			'max_file_size_attachment'          => Mage::getStoreConfig($pathGeneral . 'max_file_size_attachment'),
            'customer_can_delete_attachments'   => Mage::getStoreConfig($pathGeneral . 'can_delete_attachments'),
            'customer_can_edit_attachments'     => Mage::getStoreConfig($pathGeneral . 'can_edit_attachments'),
            'customer_can_add_new_attachments'  => Mage::getStoreConfig($pathGeneral . 'can_add_new_attachments'),
			'custom_text'                       => Mage::getStoreConfig($pathGeneral . 'custom_text'),
			'customer_group_upload_attachment'  => Mage::getStoreConfig($pathGeneral . 'customer_group_upload_attachment'),
			'email_notification'                => Mage::getStoreConfig($pathGeneral . 'receive_email_notification'),
			'admin_name'                        => Mage::getStoreConfig($pathGeneral . 'order_attachments_name'),
			'order_attachments_email'           => Mage::getStoreConfig($pathGeneral . 'order_attachments_email'),
			'notify_customer'        			=> Mage::getStoreConfig($pathGeneral . 'notify_customer'),
        );
        return $config;
    }
	
	public function getCanAddNewAttachments() {
        $config = $this->getConfiguration();
        return (bool) $config['customer_can_add_new_attachments'];
    }

    public function getCanEditAttachments() {
        $config = $this->getConfiguration();
        return (bool) $config['customer_can_edit_attachments'];
    }

    public function getCanDeleteAttachments() {
        $config = $this->getConfiguration();
        return (bool) $config['customer_can_delete_attachments'];
    }
	
	public function getCanSaveAttachmentsForOrder() {
		return (bool) ($this->getCanDeleteAttachments())
            || ($this->getCanEditAttachments())
            || ($this->getCanAddNewAttachments());
    }
	
	public function getOrderPermissionStatus() {
		$config = $this->getConfiguration();
		return array('del' => $this->getCanDeleteAttachments(),'edit' => $this->getCanEditAttachments(),'add' => $this->getCanAddNewAttachments());
		
    }
	
	public function getFilePath(){
        $config = $this->getConfiguration();
        return Mage::getBaseDir('media'). "/nborderattachments/" . DS .$config['attachment_dir'];
    }
	
	public function getPath() {
		$config = $this->getConfiguration();
		return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB). "media/nborderattachments/" . DS .$config['attachment_dir'];
	}
	
	public function getNumOfAllowedAttachment() {
		$config = $this->getConfiguration();
		if($config['num_allowed_attachment'] === '') return 10000;
		return (int) $config['num_allowed_attachment'];
	}
	
	public function getAllowedExtension() {
		$config = $this->getConfiguration();
		$extension = "jpg,gif,jpeg,png,bmp"; 
		if(empty($config['allowed_extension'])) {
			return $extension;
		} else {
			return $config['allowed_extension'];
		}
	}
	
	public function getCustomText() {
		$config = $this->getConfiguration();
		return (string) $config['custom_text'];
	}
	
	public function getCustomersAllowed($customer) {
		if(!Mage::getSingleton('customer/session')->isLoggedIn()) {
			$customerId = 0;
		} 
		else {
			$customerId = $customer->getGroupId();
		}
		$config = $this->getConfiguration();
		if(empty($config['customer_group_upload_attachment'])) return true;
		
		$allowedCustomer = explode(',',$config['customer_group_upload_attachment']);
		if(in_array($customerId,$allowedCustomer)) return true;
		
		return false;
	}
	
	public function getFileSize() {
		$config = $this->getConfiguration();
		if(empty($config['max_file_size_attachment'])) {
			return 50;
		} else {
			return $config['max_file_size_attachment'];
		}
	}
	
	public function getEmailAddress() {
		$config = $this->getConfiguration();
		return $config['order_attachments_email'];
	}
	
	public function getName() {
		$config = $this->getConfiguration();
		return $config['admin_name'];
	}
	
	public function canSendNotification() {
		$config = $this->getConfiguration();
		return $config['email_notification'];
	}
	
	public function canNotifyCustomer(){
		$config = $this->getConfiguration();
		return $config['notify_customer'];
	}
	
	static public function sendMail($attachment,$update) {  
		if(!Mage::getStoreConfig(self::XML_PATH_ORDERATTACHMENTS_ENABLED)) return;
		
		if(empty($attachment)) return;
	
		try {
			$post = array();
			$table = "<table style='border-collapse:collapse;width: 100%;border: 1px solid #bebcb7; '>";
			$table .= "<col width='45%'/>";
			$table .= "<col width='65%'/>";
			$table .= "<tr style='background: #f8f7f5 !important;border-bottom: 1px solid #c2d3e0;'>";
			$table .= "<th style='background:#dee5e8;font-weight: bold;padding: 1px 3px;border-right: 1px solid  #c2d3e0 '>File</th>";
			$table .= "<th style='background:#dee5e8;font-weight: bold;padding: 1px 3px;border-right: 1px solid  #c2d3e0'>Comment</th>";
			$table .= "</tr>";
			
			foreach ($attachment as $val) {
				$attachmentId = $val->getOrderAttachmentsId();
				$orderId = $val->getOrderId();
				$comment = $val->getComment();
				$path = Mage::helper('orderattachments')->getPath().$val->getFile();
				$attachment = basename($val->getFile());
				$table .= "<tr style='background: #f8f7f5 !important;border-bottom: 1px solid #c2d3e0;'>";
				$table .= "<td style='padding: 1px 3px;border-right: 1px solid #c2d3e0;'><a href='".$path. "'>".$attachment."</a> </td>";
				$table .= "<td style='padding: 1px 3px;border-right: 1px solid #c2d3e0;'>".$comment." </td>";				
				$table .= "</tr>";
			}

			$table .= "</table>";
			$order = Mage::getModel('sales/order')->load($orderId);
			$incrementId = $order->getIncrementId();
			$post['id'] = $orderId;									
			$post['order_id'] = $incrementId;
			$post['html'] = $table;
			$post['update'] = $update;
			$storeId = Mage::app()->getStore()->getId();
			$templateId = Mage::getStoreConfig(self::XML_PATH_ORDERATTACHMENTS_EMAIL_TEMPLATE);
			$emailTemplate = Mage::getModel('core/email_template')->loadDefault($templateId);
			
			if($update == "customer"){
				if(!Mage::helper('orderattachments')->getEmailAddress()) return;
				$senderMail = $order->getCustomerEmail();
				$senderName  = $order->getCustomerName();
				$receiverMail = Mage::helper('orderattachments')->getEmailAddress();
				$receiverName = Mage::helper('orderattachments')->getName();
				$emailTemplate->setTemplateSubject("Order Attachments For Order #" . $incrementId);
				$emailTemplate->setSenderEmail($senderMail, $storeId);
				$emailTemplate->setSenderName($senderName, $storeId);
				$emailTemplate->send($receiverMail,$receiverName, $post);
			}	
			if($update == "admin") {
				$receiverMail = $order->getCustomerEmail();
				$receiverName  = $order->getCustomerName();
				$senderMail = Mage::getStoreConfig('trans_email/ident_general/email');
				$senderName = Mage::getStoreConfig('trans_email/ident_general/name');
				$emailTemplate->setTemplateSubject("Order Attachments For Order #" . $incrementId);
				$emailTemplate->setSenderEmail($senderMail, $storeId);
				$emailTemplate->setSenderName($senderName, $storeId);
				$emailTemplate->send($receiverMail,$receiverName, $post);
			}		
		} 
		catch (Exception $e) {
			echo "<pre>";
			print_R($e);
			$errorMessage = $e->getMessage();
			return $errorMessage;
		}
	}
}
?>
