<?php
class Emipro_Customoptions_Model_Resource_Product_Option_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract 
{
  protected function _construct()
    {
            $this->_init('emipro_customoptions/product_option');
    }
     public function getSelectCountSql() {
        $countSelect = parent::getSelectCountSql();

        $countSelect = clone $this->getSelect();
        $countSelect->reset(Zend_Db_Select::ORDER);
        $countSelect->reset(Zend_Db_Select::LIMIT_COUNT);
        $countSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
        $countSelect->reset(Zend_Db_Select::COLUMNS);

        if (count($this->getSelect()->getPart(Zend_Db_Select::GROUP)) > 0) {
            $countSelect->reset(Zend_Db_Select::GROUP);
            $countSelect->distinct(true);
            $group = $this->getSelect()->getPart(Zend_Db_Select::GROUP);
            $countSelect->columns("COUNT(DISTINCT " . implode(", ", $group) . ")");
        } else {
            $countSelect->columns('COUNT(*)');
        }

        return $countSelect;
    }
}
