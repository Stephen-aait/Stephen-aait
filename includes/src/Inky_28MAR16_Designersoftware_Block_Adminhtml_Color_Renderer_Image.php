<?php
class Inky_Designersoftware_Block_Adminhtml_Color_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $data = $row->getData();
        
        $collection = Mage::getModel('designersoftware/texture')->getCollection()->addFieldToFilter('texture_id',$data['texture_id'])->getFirstItem();
                
		$imagePath=Mage::helper('designersoftware/image')->_thumbWebPath(45,45,'texture') . $collection->getFilename();		
		$imageHtml=file_exists(Mage::helper('designersoftware/image')->_thumbDirPath(45,45,'texture') . $collection->getFilename()) && $collection->getFilename()!=''?'<img src="'.$imagePath.'">':'';
       
        return $imageHtml;
    }
}
?>
