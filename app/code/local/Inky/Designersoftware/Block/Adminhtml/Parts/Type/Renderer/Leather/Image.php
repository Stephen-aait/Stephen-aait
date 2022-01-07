<?php
class Sparx_Designersoftware_Block_Adminhtml_Parts_Layers_Renderer_Leather_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $data = $row->getData();
                
		$filename=Mage::getModel('designersoftware/leather')->getCollection()
						->addFieldToFilter('leather_id',$data['leather_id'])
						->getFirstItem()
						->getFilename();
						
		$imagePath=Mage::helper('designersoftware/image')->_thumbWebPath(45,45,'leather') . $filename;		
		$imageHtml=file_exists(Mage::helper('designersoftware/image')->_thumbDirPath(45,45,'leather') . $filename) && $filename!=''?'<img src="'.$imagePath.'">':'';
		
		return $imageHtml;
    }
}
?>
