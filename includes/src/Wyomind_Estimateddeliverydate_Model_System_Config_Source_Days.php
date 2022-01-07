<?php

class Wyomind_Estimateddeliverydate_Model_System_Config_Source_Days {

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray() {


        $days = array();
        $days[] = array("value" => '1', 'label' => Mage::helper("estimateddeliverydate")->__("Monday"));
        $days[] = array("value" => '2', 'label' => Mage::helper("estimateddeliverydate")->__("Tuesday"));
        $days[] = array("value" => '3', 'label' => Mage::helper("estimateddeliverydate")->__("Wednesday"));
        $days[] = array("value" => '4', 'label' => Mage::helper("estimateddeliverydate")->__("Thursday"));
        $days[] = array("value" => '5', 'label' => Mage::helper("estimateddeliverydate")->__("Friday"));
        $days[] = array("value" => '6', 'label' => Mage::helper("estimateddeliverydate")->__("Saturday"));
        $days[] = array("value" => '0', 'label' => Mage::helper("estimateddeliverydate")->__("Sunday"));


        return $days;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray() {


        $days = array();
        $days[] = array("value" => '1', 'label' => Mage::helper("estimateddeliverydate")->__("Monday"));
        $days[] = array("value" => '2', 'label' => Mage::helper("estimateddeliverydate")->__("Tuesday"));
        $days[] = array("value" => '3', 'label' => Mage::helper("estimateddeliverydate")->__("Wednesday"));
        $days[] = array("value" => '4', 'label' => Mage::helper("estimateddeliverydate")->__("Thursday"));
        $days[] = array("value" => '5', 'label' => Mage::helper("estimateddeliverydate")->__("Friday"));
        $days[] = array("value" => '6', 'label' => Mage::helper("estimateddeliverydate")->__("Saturday"));
        $days[] = array("value" => '0', 'label' => Mage::helper("estimateddeliverydate")->__("Sunday"));

        return $days;
    }

}
