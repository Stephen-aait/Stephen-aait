<?php
class Inky_Designersoftware_Block_Adminhtml_Designersoftware_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $data = $row->getData();
        
        $commPath = 'media' . DS . 'inky' . DS . 'designs' . DS . $data['style_design_code']. DS . 'original' . DS;
        $fileDirPath = Mage::getBaseDir() . DS . $commPath . '000.png';   
	    $fileWebPath = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . $commPath . '000.png';    
        $imageHtml=file_exists($fileDirPath)?'<p><img src="' . $fileWebPath . '" width="100"/></p>':'';        
		
		return $imageHtml;
    }
    
    public function getAngle(){				
		return Mage::helper('designersoftware/adminhtml_system_config')->getAngle();		
	}
	
	public function getImageExt(){
		return Mage::helper('designersoftware/image')->getImageExt();
	}
}
?>
