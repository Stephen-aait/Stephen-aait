<?php
class Inky_Designersoftware_Block_Adminhtml_Clipart_Upload_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $data = $row->getData();
                
		$imagePath=Mage::helper('designersoftware/image')->_thumbWebPath(51,53) . $data['filename'];		
		$imageHtml=file_exists(Mage::helper('designersoftware/image')->_thumbDirPath(51,53) . $data['filename']) && $data['filename']!=''?'<a href="'. Mage::helper('designersoftware/image')->_originalWebPath() . 'original'. DS . $data['filename'].'" target="_blank"><img src="'.$imagePath.'"></a><br><a href="'. Mage::helper('designersoftware/image')->_originalWebPath() . 'original'. DS . $data['filename'].'" target="_blank">Download Clipart</a>':'';
        
        return $imageHtml;
    }
}
?>
