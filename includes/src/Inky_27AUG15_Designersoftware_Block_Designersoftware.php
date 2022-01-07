<?php
class Inky_Designersoftware_Block_Designersoftware extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
    // Check Store name is exist in URL or not
    public function checkStoreInUrl(){
		$storeInUrlCheck = Mage::getStoreConfig('web/url/use_store');
		if($storeInUrlCheck):
			return true;
		else:
			return false;
		endif;
	}
    
    // Get Url to access file Web path
    public function getWebPath(){		
		if($this->checkStoreInUrl()):
			$storeInUrlAdd = '../';		
		else:
			$storeInUrlAdd = '';
		endif;
		
		return Mage::getBaseUrl() . $storeInUrlAdd;
	}
	
	// Get base64encoded params and convert it to params Array
	public function getParams($REQUEST){
		return Mage::helper('designersoftware/product')->getParams($REQUEST);		
	}
    
    public function getDesignersoftware()     
    { 
        if (!$this->hasData('designersoftware')) {
            $this->setData('designersoftware', Mage::registry('designersoftware'));
        }
        return $this->getData('designersoftware');        
    }
    
    public function getStyles(){
		$collection = Mage::getModel('designersoftware/style')->getCollection()->setOrder('sort_order','ASC')->addFieldToFilter('status',1);
		
		return $collection;
	}     
	
	public function getStyleDesignImage($styleId){		
		$collection = Mage::getModel('designersoftware/style_design')->getCollection()
							->addFieldToFilter('style_id',$styleId)							
							->addFieldToFilter('status',1)
							->getFirstItem();

		//echo '<pre>';print_r($collection->getData());exit;
		$imageUrl = Mage::helper('designersoftware/image')->createCompositeStyleDesignDirPath($collection->getCode(), unserialize($collection->getPartsLayersIds()));
		//echo $imageUrl['web'];exit;
		return $imageUrl['web'];
	}
	
	public function getStyleDesignCode($styleId){		
		$collection = Mage::getModel('designersoftware/style_design')->getCollection()
							->addFieldToFilter('style_id',$styleId)							
							->addFieldToFilter('status',1)
							->getFirstItem();
		
		$code = $collection->getCode();
		//echo $imageUrl['web'];exit;
		return $code;
	}
}
