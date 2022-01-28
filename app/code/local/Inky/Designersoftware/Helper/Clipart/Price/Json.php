<?php

class Inky_Designersoftware_Helper_Clipart_Price_Json extends Mage_Core_Helper_Abstract
{
    public function load($data)
    {
            
        $priceDetails = array();

        $clipartPriceCollection  = Mage::getModel('designersoftware/clipart_price')->getCollection()
                                                        ->addFieldToFilter('status', 1);
                            
        foreach ($clipartPriceCollection as $price) :
            $array['id']            = $price->getId();
            $array['width']         = $price->getWidth();
            $array['height']        = $price->getHeight();
            $array['widthPrice']    = $price->getWidthPrice();
            $array['heightPrice']   = $price->getHeightPrice();
            
            $priceDetails[] = $array;
        endforeach;
            
            $finalJSON['sizeRange'] = $priceDetails;
            
        return $finalJSON;
    }
}
