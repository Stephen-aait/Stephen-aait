<?php
class Emipro_Customoptions_Block_Adminhtml_Customoptions_Grid_Renderer_EditCategory extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $categoryUrl = $this->getUrl("*/*/editcategory", array("id" => $row->getId()));
        return '<a href="' . $categoryUrl . '">' . $this->__('Assign to Category / Remove from Category') . '</a>';
    }

}
