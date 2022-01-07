<?php

class Inky_Designersoftware_Block_Adminhtml_Designersoftware_Renderer_Sort extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $data = $row->getData();
        //print_r($data);exit;
		
		$html ='<input class="massaction-input" type="text" name="sort_order" id="'. $data["designersoftware_id"].'" value="'. $data["sort_order"].'" style="margin-left:1px;width:65px;"/>';
		
		return $html;
    }
}

?>
