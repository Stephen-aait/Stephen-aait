<?php
abstract class Inky_Designersoftware_Model_Abstract extends Mage_Core_Model_Abstract
{	
	// Designerproduct Values for Installer
	const DESIGNER_PRODUCT_ATTRIBUTE_SET_LABLE 	= 'Designer Software';
	const DESIGNER_PRODUCT_NAME					= 'Lettering Designer Product';
	const DESIGNER_PRODUCT_SKU					= 'LDP-00000001';
	const DESIGNER_PRODUCT_CUSTOM_OPTION_LABLE 	= 'Design Code';
	
	const DESIGNER_PRODUCT_ID_VARIABLE			= 'dId';	
	
	
	const DESIGNER_PRODUCT_MAIN_DIRECTORY 		= 'Inky';
	const DESIGNER_PRODUCT_SUB_DIRECTORY 		= 'Designs';
	
	const DESIGNER_PRODUCT_EDIT_DESIGN_URL		= 'case-designer-product.html?';
	const DESIGNER_PRODUCT_TOOL_URL				= 'tool.html?';
		
	public function getAttributeSetLabel(){
		
		return self::DESIGNER_PRODUCT_ATTRIBUTE_SET_LABLE;		
		
	}
	
	public function getDesignerProductSku(){
		
		return self::DESIGNER_PRODUCT_SKU;		
			
	}
	
	public function getCustomOptionLabel(){
		
		return self::DESIGNER_PRODUCT_CUSTOM_OPTION_LABLE;		
		
	}
	
	public function getDesignerProductDetailsUrl($params){
		
		return Mage::getBaseUrl() . self::DESIGNER_PRODUCT_EDIT_DESIGN_URL . $params;
		
	}
	
	// Edit Url for Designer product will open the product in Designer Tool with specified Id
	public function getDesignerProductEditUrl($params, $type){		
	
		if($type=="cart"):
			return Mage::getBaseUrl() . self::DESIGNER_PRODUCT_EDIT_DESIGN_URL . $params;		
		else:
			return Mage::getBaseUrl() . self::DESIGNER_PRODUCT_TOOL_URL . $params;		
		endif;
		
	}
	
	// Get Product Code from Option List
	public function getDesignerProductCode($_options){
		
		$label = self::DESIGNER_PRODUCT_CUSTOM_OPTION_LABLE;
		
		if($_options):        
			foreach($_options as $_option):
				if(strtolower($_option['label'])==strtolower($label)):
					$designCode = trim($_option['value']);					
					break;
				endif;
			endforeach;        
		endif;
		
		return $designCode;
	}
	
	
	public function getDesignerProduct($designCode){		
		
		// DesignerSoftware Model Collection
		$collection = Mage::getModel('designersoftware/designersoftware')->getDesignerProductByCode($designCode);
		return $collection;

	}
	 
	
	public function getDesignerProductParams($designerProductCollection){								
	
		$params='';
		//if($designerProductCollection->getId() > 0):						
			//$params .= DS . self::DESIGNER_PRODUCT_ID_VARIABLE . DS . $designerProductCollection->getId();																	
		//endif;
		
		return Mage::helper('designersoftware/product')->setParams($designerProductCollection->getId());
		
	}	
	
	public function getDesignerProductImage($designSku, $image){
		 
		 //$path = DS . strtolower(self::DESIGNER_PRODUCT_MAIN_DIRECTORY) . DS . strtolower(self::DESIGNER_PRODUCT_SUB_DIRECTORY) . DS . $image;
		 //$path = DS . strtolower(self::DESIGNER_PRODUCT_MAIN_DIRECTORY) . DS . strtolower(self::DESIGNER_PRODUCT_SUB_DIRECTORY) . DS . $image;
		 $path = DS .'inky/designs/'.$designSku.'/original/000.png';
		 
		 return $path;
	}
}
