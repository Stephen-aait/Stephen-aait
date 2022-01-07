<?php
class Emipro_Customoptions_Block_Adminhtml_Catalog_Product_Edit_Tab_Options_Type_File extends
    Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Options_Type_File
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('customoptions/product/file.phtml');
    }
}
