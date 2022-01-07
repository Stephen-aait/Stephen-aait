<?php
/**
 * @category    Genmato
 * @package     Genmato_Core
 * @copyright   Copyright (c) 2014 Genmato BV (https://genmato.com)
 */

class Genmato_Core_Model_System_Config_Backend_Serialized_Array extends Mage_Adminhtml_Model_System_Config_Backend_Serialized_Array
{
    /**
     * Unset array element with '__empty' key
     *
     */
    protected function _beforeSave()
    {
        $value = $this->getValue();
        if (is_array($value)) {
            unset($value['__empty']);
        }
        foreach ($value as $id => $fields) {
            foreach ($fields as $key => $val) {
                if (is_array($val)) {
                    $value[$id][$key] = implode(',', $val);
                }
            }
        }
        $this->setValue($value);
        parent::_beforeSave();
    }
}
