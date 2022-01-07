<?php
class Emipro_Customoptions_Block_Adminhtml_Customoptions_Grid_Renderer_Sku extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {

        $sku = Mage::getModel("catalog/product_option")->load($row->getId())->getData("sku");
        $productUrl = $this->getUrl("*/*/edit", array("id" => $row->getId(), "sku" => $sku,"skulist"=>1));
        $categoryUrl = $this->getUrl("*/*/editcategory", array("id" => $row->getId(), "sku" => $sku));
        return '<a href="' . $productUrl . '">' . $this->__('Add To Product (SKU list)') . '</a>';
    }

}
