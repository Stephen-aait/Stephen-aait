<?php
class Emipro_Customoptions_Block_Adminhtml_Customoptions_Grid_Renderer_EditProduct extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $sku = Mage::getModel("emipro_customoptions/product_option")->load($row->getId())->getData("sku");
        $productUrl = $this->getUrl("*/*/edit", array("id" => $row->getId(), "sku" => $sku));
        return '<a href="' . $productUrl . '">' . $this->__('Assign to Product / Remove from Product') . '</a>';
    }

}
