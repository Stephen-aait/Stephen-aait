<?php

class Emipro_Customoptions_Model_Resource_Product_Option extends Mage_Core_Model_Resource_Db_Abstract {

    public function _construct() {
        $this->_init('emipro_customoptions/product_option','option_id');
    }
public function deletePrices($optionId)
    {
        $this->_getWriteAdapter()->delete(
            $this->getTable('catalog/product_option_price'),
            array(
                'option_id = ?' => $optionId
            )
        );

        return $this;
    }

}
