<?php
class Inky_Designersoftware_Block_Abstract extends Mage_Core_Block_Template
{	
	protected $_model;
	
	function __construct(){
		
		$this->getDesignersoftwareModel();		
		
	}	
	
	public function getDesignersoftwareModel(){
		
		$this->_model = Mage::getModel('designersoftware/cart');
		
	}
	
	public function getDesignerProductParams(){	
		$designersoftwareModel = $this->designersoftwareModel;			
		
		$params='';
		if($designersoftwareModel['designersoftware_id']>0):						
			$params .= DS . self::DESIGNER_PRODUCT_ID . DS . $designersoftwareModel['designersoftware_id'];																	
		endif;
		
		return $params;
	}
	
	public function getDesignerProduct(){		

	}
}
