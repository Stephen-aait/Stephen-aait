<?php

class Emipro_Customoptions_Model_Resource_Product_Option_Type_Value extends Mage_Core_Model_Resource_Db_Abstract {
    public function _construct() {
        $this->_init('emipro_customoptions/product_option_type_value','option_type_id');
    }


 public function deleteValue($optionId)
    {
        $statement = $this->_getReadAdapter()->select()
            ->from($this->getTable('emipro_customoptions/product_option_type_value'))
            ->where('option_id = ?', $optionId);

        $rowSet = $this->_getReadAdapter()->fetchAll($statement);

        foreach ($rowSet as $optionType) {
            $this->deleteValues($optionType['option_type_id']);
        }

        $this->_getWriteAdapter()->delete(
            $this->getMainTable(),
            array(
                'option_id = ?' => $optionId,
            )
        );

        return $this;
    }

    /**
     * Delete values by option type
     *
     * @param int $optionTypeId
     */
    public function deleteValues($optionTypeId)
    {
        $condition = array(
            'option_type_id = ?' => $optionTypeId
        );

        $this->_getWriteAdapter()->delete(
            $this->getTable('emipro_customoptions/product_option_type_price'),
            $condition
        );

        $this->_getWriteAdapter()->delete(
            $this->getTable('emipro_customoptions/product_option_type_title'),
            $condition
        );
    }
}
