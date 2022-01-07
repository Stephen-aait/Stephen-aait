<?php

class Wyomind_Estimateddeliverydate_Model_System_Config_Source_Countdownformat {

    
    public function toOptionArray() {
        return array(
            array('label' => 'X days', 'value' => '1'),
            array('label' => 'X days, Y hours', 'value' => '2'),
            array('label' => 'X days, Y hours, Z minutes', 'value' => '3'),
            array('label' => 'X days, Y hours, Z minutes, N seconds', 'value' => '4'),
        );
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray() {
        return array(
            array('label' => 'X days', 'value' => '1'),
            array('label' => 'X days, Y hours', 'value' => '2'),
            array('label' => 'X days, Y hours, Z minutes', 'value' => '3'),
            array('label' => 'X days, Y hours, Z minutes, N seconds', 'value' => '4'),
        );
    }
    

}