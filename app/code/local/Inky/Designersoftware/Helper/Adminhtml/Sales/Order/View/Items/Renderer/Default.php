<?php

class Inky_Designersoftware_Helper_Adminhtml_Sales_Order_View_Items_Renderer_Default extends Mage_Core_Helper_Abstract
{
	public function getColumnHtml($item, $columnName){		
		$itemInfoRequest = $item->getProductOptions();
		//Mage::log($itemInfoRequest,null,'designersoftware.log');
		$designCode = $itemInfoRequest['options']['0']['value'];		
		
		$designCollection = Mage::getModel('designersoftware/orders_design')->getCollection()
											->addFieldToFilter('style_design_code',$designCode)
											->addFieldToFilter('status',1)											
											->getFirstItem();	
		
		// Designer Tool category Id
        $designerToolCategoryId = Mage::getModel('core/variable')->loadByCode('DESIGNER_TOOL_CATEGORY_ID')->getValue('plain');
        $_categoryCollection 	= Mage::getModel('catalog/category')->load($designerToolCategoryId);
        
        $params = Mage::helper('designersoftware/product')->setParams($designCollection->getDesignersoftwareId()); 
        $imageHtml  = '';
        $imageHtml .= '<a target="_blank" href="'. Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'designersoftware/pdf/generate/designCode/'.$designCode.'/storeId/1/orderId/'. $designCollection->getOrderId() .'">Customer PDF</a><br>';
		$imageHtml .= '<a target="_blank" href="'. Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'designersoftware/pdf/generate/designCode/'.$designCode.'/storeId/2">Workshop PDF</a><br>';
		$imageHtml .= '<a href="'.Mage::app()->getStore(1)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK). $_categoryCollection->getUrlPath().'?'. $params .'" target="_blank">Edit in Tool</a>';
		
		if( $this->getCustomerUploadFileNameCheck($designCollection->getPriceInfoArr()) ){			
			$filter=base64_encode('title='.$this->_customerUploadFilename);
			$imageHtml .= '<a href="'. Mage::helper('adminhtml')->getUrl('designersoftware/adminhtml_clipart_upload/index', ['_secure' => true, 'filter'=> $filter]).'" target="_blank">Customer Upload</a>';
		}
		
		switch($columnName){
			case 'action':	
				return $imageHtml;												
			break;			
			default:
		}		
	}
	
	public function getCustomerUploadFileNameCheck($priceInfoArrString){
		$priceInfoArr = unserialize($priceInfoArrString);
		foreach( $priceInfoArr as $partsArr ){
			if ( count($partsArr) > 2 ){
				continue;
			} else {
				$this->_customerUploadFilename = $partsArr['partName'];
				return 1;
			}
		}
		return 0;
	}	
}
