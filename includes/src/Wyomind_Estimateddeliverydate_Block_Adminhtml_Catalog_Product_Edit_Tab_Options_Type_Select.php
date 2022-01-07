<?php

class Wyomind_Estimateddeliverydate_Block_Adminhtml_Catalog_Product_Edit_Tab_Options_Type_Select extends
Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Options_Type_Select {

    public function __construct() {

        $this->setTemplate('catalog/product/edit/options/type/select-leadtime.phtml');
        $this->setCanEditPrice(true);
        $this->setCanReadPrice(true);
    }

}
