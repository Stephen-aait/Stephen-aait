<?php

class Wyomind_Estimateddeliverydate_Block_Catalog_Product_View_Options extends Mage_Catalog_Block_Product_View_Options {

    public function getOptionsLeadtimes() {

        $config = array();
        foreach ($this->getOptions() as $option) {
            foreach ($option->getValues() as $value) {
                $config[$option->getId()][$value->getId()] = (int) $value->getLeadtime();
            }
        }
        return Mage::helper('core')->jsonEncode($config);
    }

}
