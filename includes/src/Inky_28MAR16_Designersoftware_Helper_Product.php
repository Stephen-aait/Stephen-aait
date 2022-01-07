<?php

class Inky_Designersoftware_Helper_Product extends Mage_Core_Helper_Abstract
{
	//Load product model collecttion filtered by attribute set id
	public function getProductsByAttributeSetId($attrSetId){				
		$products = Mage::getModel('catalog/product')
			->getCollection()
			->addAttributeToSelect('entity_id')
			->addFieldToFilter('attribute_set_id', $attrSetId);
		
		return $products;
	}	
	
	//process your products collection to get Product Id 
	public function getProductId($products){				
		
		foreach($products as $p){
			$productId = $p->getId();
			break;
		}		
		return $productId;
	}
	
	// Get base64encoded params and convert it to params Array
	public function getParams($REQUEST){
		
		if(isset($REQUEST) && !empty($REQUEST)):
			foreach($REQUEST as $params=>$val):
				$params = $params;
				break;	
			endforeach;
		
			$params						= base64_decode($params);
			$paramsStringArray			= explode('&',$params);
			
			foreach($paramsStringArray as $value):
				$paramArray = explode('=',$value);
				$paramsArray[trim($paramArray[0])] = trim($paramArray[1]);		
			endforeach;
			
			return $paramsArray;
		else:
			return false;
		endif;
	}
	
	public function setParams($designersoftwareId, $mode='edit', $user=''){
		
		$params='';
		
		// Design Id
		if($designersoftwareId>0):			
			$params .='did='.$designersoftwareId;			
		endif;
		
		// Mode Type
		if(!empty($mode)):
			$params .='&';
			$params .='mode='.$mode; 
		endif;
		
		// User Type
		if(!empty($user)):
			$params .='&';
			$params .='user='.$user;
		endif;
		
		$params = base64_encode($params);
		
		return $params;
	}
	
	public function addProductToWishlist($customerId, $productId, $options, $quantity){
		$wishlist=Mage::helper('wishlist')->getWishlist();
		//$wishlist=Mage::getModel('wishlist/wishlist');
		$storeId = Mage::app()->getStore()->getId();
		
		$model = Mage::getModel('catalog/product');
		$_product = $model->load($productId); 
		$params = array('product' => $productId,
						'qty' => $quantity,
						'store_id' => $storeId,
						'options' => array($options['id']=>$options['value'])
						);
						
		 $request = new Varien_Object();
		 $request->setData($params);
		 $result = $wishlist->addNewItem($_product, $request);	
	
	}	
	
	
	public function getStyleParams($designId,$type){				
		if($designId>0):
								
			$params='';			
			$params .='did='.$designId;
			$params .='&';
			$params .='mode=edit'; 
			$params .='&';
			$params .='productType='.$type;
			
			$params = base64_encode($params);			
			return $params;
		endif;
	}
	
}
