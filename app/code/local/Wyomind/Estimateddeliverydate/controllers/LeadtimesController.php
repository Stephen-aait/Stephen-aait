<?php

class Wyomind_Estimateddeliverydate_LeadtimesController extends Mage_Core_Controller_Front_Action {

    public function optionsAction() {
        $data = (Mage::app()->getRequest()->getParams());
        $from = 0;
        $storeId = Mage::app()->getStore()->getId();
        $method = Mage::getStoreConfig("estimateddeliverydate/custom_options/calculation", $storeId);

        foreach (Mage::helper("core")->jsonDecode($data["leadtime_from"]) as $f) {
            if ($method)
                $from+=$f;
            else
            if ($f > $from)
                $from = $f;
        }
        $to = 0;
        foreach (Mage::helper("core")->jsonDecode($data["leadtime_to"]) as $t) {
            if ($method)
                $to+=$t;
            else
            if ($t > $to)
                $to = $t;
        }
        if ($data["elt"] == "leattimes2")
            echo Mage::helper("estimateddeliverydate")->getProductMessage2(Mage::getModel('catalog/product')->load($data['id']), $storeId, $from, $to);
        else
            echo Mage::helper("estimateddeliverydate")->getProductMessage(Mage::getModel('catalog/product')->load($data['id']), $storeId, $from, $to);
    }

}
