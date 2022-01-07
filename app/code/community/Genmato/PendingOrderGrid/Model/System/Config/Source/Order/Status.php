<?php

/**
 * @category    Genmato
 * @package     Genmato_PendingOrderGrid
 * @copyright   Copyright (c) 2014 Genmato BV (https://genmato.com)
 */
class Genmato_PendingOrderGrid_Model_System_Config_Source_Order_Status
{

    public function toOptionArray()
    {
        $statuses = Mage::getSingleton('sales/order_config')->getStatuses();

        $options = array();
        foreach ($statuses as $code => $label) {
            $options[] = array(
                'value' => $code,
                'label' => $label
            );
        }
        return $options;
    }

    public function toOptionHash()
    {
        $statuses = Mage::getSingleton('sales/order_config')->getStatuses();

        $options = array();
        foreach ($statuses as $code => $label) {
            $options[$code] = $label;
        }
        return $options;
    }
}