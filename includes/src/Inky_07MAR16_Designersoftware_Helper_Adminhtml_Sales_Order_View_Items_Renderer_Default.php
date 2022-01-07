<?php

class Inky_Designersoftware_Helper_Adminhtml_Sales_Order_View_Items_Renderer_Default extends Mage_Core_Helper_Abstract
{
	public function getColumnHtml($item, $columnName){		
		$itemInfoRequest = $item->getProductOptions();
		//Mage::log($itemInfoRequest,null,'designersoftware.log');
		$designCode = $itemInfoRequest['options']['0']['value'];		
		
		$designCollection = Mage::getModel('designersoftware/designersoftware')->getCollection()
											->addFieldToFilter('style_design_code',$designCode)
											->addFieldToFilter('status',1)											
											->getFirstItem();	
		
		// Designer Tool category Id
        $designerToolCategoryId = Mage::getModel('core/variable')->loadByCode('DESIGNER_TOOL_CATEGORY_ID')->getValue('plain');
        $_categoryCollection 	= Mage::getModel('catalog/category')->load($designerToolCategoryId);
        
        $params = Mage::helper('designersoftware/product')->setParams($designCollection->getId()); 
        $imageHtml  = '';
        $imageHtml .= '<a target="_blank" href="'. Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'designersoftware/pdf/generate/designCode/'.$designCode.'/storeId/1/orderId/'. $orderId .'">Customer PDF</a><br>';
		$imageHtml .= '<a target="_blank" href="'. Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'designersoftware/pdf/generate/designCode/'.$designCode.'/storeId/2">Workshop PDF</a><br>';
		$imageHtml .= '<a href="'.Mage::app()->getStore(1)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK). $_categoryCollection->getUrlPath().'?'. $params .'" target="_blank">Edit in Tool</a>';
		
		switch($columnName){
			case 'action':	
				return $imageHtml;												
			break;			
			default:
		}
		
	}
	
}
