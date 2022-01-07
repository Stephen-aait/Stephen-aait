<?php

class Inky_Designersoftware_Model_Adminhtml_System_Config_Source_Angles extends Varien_Object
{
    static public function toOptionArray()
    {
        $collection = Mage::getModel('designersoftware/angles')->getAnglesCollection();
        if(count($collection)>0){
            foreach($collection as $angle){
                $angleArray[$angle->getTitle()] = $angle->getTitle();
            }
        }
        
        return $angleArray;
    }
}
