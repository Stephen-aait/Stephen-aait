<?php
class Emipro_Customoptions_Model_Resource_Product_Option_Price_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract 
{
  protected function _construct()
    {
            $this->_init('emipro_customoptions/product_option_price');
    }
}
