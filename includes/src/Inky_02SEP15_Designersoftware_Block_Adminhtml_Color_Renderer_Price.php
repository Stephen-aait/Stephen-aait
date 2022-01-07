<?php

class Inky_Designersoftware_Block_Adminhtml_Color_Renderer_Price extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
		$partsLayersid = $this->getRequest()->getParams('id');
		
        $data = $row->getData();        
        $price = Mage::getModel('designersoftware/parts_layers')->getColorInfoColorPrice($partsLayersid, $row->getId()); 
               
        $html ='<input class="input-text validate-number" tabindex="1000" type="text" name="color_price['. $row->getId() .']" id="color_price['. $row->getId() .']" value="'.$price.'" style="width:50px;"/>';
        
        return $html;
    }
    
}

?>
