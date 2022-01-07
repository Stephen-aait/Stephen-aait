<?php

class Inky_Designersoftware_Helper_Customer extends Mage_Core_Helper_Abstract
{
   
   public function getCurrentCustomerSession(){
	   // Get current user session Id on Website
	  $session = Mage::getSingleton("customer/session");
	  
	  return $session;
   }
   
   // get Current not logged in customer session ID
   public function getCurrentSessionId(){
	   
		$session = $this->getCurrentCustomerSession();
		$sessionId = $session->getEncryptedSessionId(); 
	  
		return $sessionId;
   }
   
   public function getCurrentCustomerId(){
	   $session = $this->getCurrentCustomerSession();
	   $customerId = $session->getId();
	   
	   if($customerId>0):
			return $customerId;
	   else:
			return false;
	   endif;
   }
   
   public function getCurrentCustomerName(){
	   $session = $this->getCurrentCustomerSession();
	   $customerName = $session->getCustomer()->getName();
	   
	   return $customerName;
	   
   }
   
   public function getCustomerNameById($customerId){
	   $collection = Mage::getModel('customer/customer')->load($customerId);
	   $customerName = $collection->getName();
	   
	   return $customerName;	   
   }
   
   public function addProductToWishlist(){	   
	   if($this->getCurrentCustomerId()):
			$customerId		= $this->getCurrentCustomerId();
			
			$designersoftwareModel = Mage::getSingleton('designersoftware/designersoftware');			
			$session = Mage::getSingleton("designersoftware/session");			
			$designersoftwareCollection = $session->getDesignData();
			//echo '<pre>';print_r($designersoftwareCollection);exit;
			
			if(count($designersoftwareCollection)>0):
				foreach($designersoftwareCollection as $designersoftware):
					$designId		= $designersoftware['designId'];
					$productId 		= $designersoftware['product_id'];
					$styleDesignCode= $designersoftware['style_design_code'];
					$options 		= Mage::helper('designersoftware/product_customoption')->sendCustomOptionArray($productId, $styleDesignCode);
					$quantity 		= 1;
				
					Mage::helper('designersoftware/product')->addProductToWishlist($customerId, $productId, $options, $quantity);
					
					// Update Design Data for Customer
					$designersoftware['customer_id'] = $customerId;
					$designersoftwareModel->setCustomerId($designersoftware);
					$designersoftwareModel->setId($designId);
					
					$designersoftwareModel->save();
				endforeach;
			endif;
			
			$session->unsDesignData();
	   endif;
   } 
   
   public function getClipartCustomer(){
	   $clipartCustomerArr=array();
		//$texturetypeArr['']='Select Category';
		$collection = Mage::getModel('designersoftware/clipart_upload')->getCollection()
			->addFieldToFilter('status',array('eq'=>1));
			//->setOrder('sort_order',asc);
		if($collection->getSize()>0){
			foreach($collection->getData() as $value){
				if($value['customer_id']>0):
					$title = $this->getCustomerNameById($value['customer_id']);
					$clipartCustomerArr[$value['customer_id']]= $title;	
				endif;
			}
		}
		return $clipartCustomerArr;
   }
   
   public function getDesignerCustomer(){
	   $clipartCustomerArr=array();
		//$texturetypeArr['']='Select Category';
		$collection = Mage::getModel('designersoftware/designersoftware')->getCollection()
			->addFieldToFilter('status',array('eq'=>1));
			//->setOrder('sort_order',asc);
		if($collection->getSize()>0){
			foreach($collection->getData() as $value){
				if($value['customer_id']>0):
					$title = $this->getCustomerNameById($value['customer_id']);
					$clipartCustomerArr[$value['customer_id']]= $title;	
				endif;
			}
		}
		return $clipartCustomerArr;
   }
   
   public function getAllCustomers(){
	   $clipartCustomerArr=array();
		$clipartCustomerArr['']='';
		$collection = Mage::getModel('customer/customer')->getCollection()
								->addAttributeToSelect('firstname')
								->addAttributeToSelect('lastname')
								->setOrder('firstname',asc);
								
		if($collection->getSize()>0){
			foreach($collection as $collections){				
				$clipartCustomerArr[$collections->getId()]= $collections->getName();	
			}
		}
		
		return $clipartCustomerArr;
   }

}
