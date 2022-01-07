<?php

class Inky_Designersoftware_Block_Adminhtml_Color_Renderer_Color extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $data = $row->getData();

        $color_code=$data['colorcode'];
        $html ='<table><tr style="height:30px;"><td width="10%">#'.$color_code.'</td><td bgcolor="#'.$color_code.'"></td></tr></table> ';
         
        return $html;
    }


}

?>
