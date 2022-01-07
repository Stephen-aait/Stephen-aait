<?php

class Wyomind_Estimateddeliverydate_Model_System_Config_Source_Scope {

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray() {


        $scopes = array();
        $scopes[] = array("value" => '1', 'label' => "Orders and backorders");
        $scopes[] = array("value" => '2', 'label' => "Orders only");
        $scopes[] = array("value" => '3', 'label' => "Backorders only");

        return $scopes;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray() {


        $scopes = array();
        $scopes[] = array("value" => '1', 'label' => "Orders and backorders");
        $scopes[] = array("value" => '2', 'label' => "Orders only");
        $scopes[] = array("value" => '3', 'label' => "Backorders only");

        return $scopes;
    }

}
