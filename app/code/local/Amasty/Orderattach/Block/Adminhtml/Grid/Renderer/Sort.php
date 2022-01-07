<?php

class Amasty_Orderattach_Block_Adminhtml_Grid_Renderer_Sort extends Amasty_Orderattach_Block_Adminhtml_Grid_Renderer_StatusAbstract {

    public function render(Varien_Object $row) {
        $data = $row->getData();
        //print_r($data);exit;
		
		$html ='<input class="massaction-input" type="text" name="sort_order" id="'. $data["field_id"].'" value="'. $data["sort_order"].'" style="margin-left:1px;width:65px;"/>';
		
		return $html;
    }
}

?>
