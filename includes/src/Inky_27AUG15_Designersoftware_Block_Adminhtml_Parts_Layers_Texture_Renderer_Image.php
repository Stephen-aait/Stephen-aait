<?php
class Inky_Designersoftware_Block_Adminhtml_Texture_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $data = $row->getData();
                
		$imagePath=Mage::helper('designersoftware/image')->_thumbWebPath(45,45,'texture') . $data['filename'];		
		$imageHtml=file_exists(Mage::helper('designersoftware/image')->_thumbDirPath(45,45,'texture') . $data['filename']) && $data['filename']!=''?'<img src="'.$imagePath.'">':'';
       
        return $imageHtml;
    }
}
?>
