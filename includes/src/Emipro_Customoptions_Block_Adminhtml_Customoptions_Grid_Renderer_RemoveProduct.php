<?php
class Emipro_Customoptions_Block_Adminhtml_Customoptions_Grid_Renderer_RemoveProduct extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $productUrl = $this->getUrl("*/*/editsku", array("id" => $row->getId(),"remove"=>1));
        return '<a href="' . $productUrl . '">' . $this->__('Assign to product / Remove From Product (SKU list)') . '</a>';
    }

}
