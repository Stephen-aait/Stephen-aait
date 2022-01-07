<?php

class Inky_Designersoftware_Block_Adminhtml_Font_Renderer_Font extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $data = $row->getData();

        $fontImage=$data['ttf_image_name'];
        $html ='<img src="'.Mage::getBaseUrl('media') .  'inky' . DS . 'font' . DS . 'image' . DS .$fontImage.'">';
         
        return $html;
    }


}

?>
