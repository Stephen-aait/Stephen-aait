<?php
class Emipro_Customoptions_Block_Adminhtml_Catalog_Product_Edit_Tab_Options_Type_Text extends
    Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Options_Type_Text
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('customoptions/product/text.phtml');
    }
}
