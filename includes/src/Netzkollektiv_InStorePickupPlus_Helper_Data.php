<?php
class Netzkollektiv_InStorePickupPlus_Helper_Data extends Mage_Core_Helper_Abstract {
    
    public function getPaymentMethods($storeId = null)
    {
        $options = array();
        foreach (Mage::helper('payment')->getPaymentMethods($storeId) as $code => $methodConfig)
        {
            if (!$model = Mage::getStoreConfig('payment/' . $code . '/' . 'model', $storeId)) {
                continue;
            }
            $methodInstance = Mage::getModel($model);
            if (!$methodInstance) {
                continue;
            }
           
            $label = $methodInstance->getConfigData('title');
            if(!$label) {
                $label = $methodInstance->getCode();
            }

            array_unshift($options, array(
                'value' => $methodInstance->getCode(),
                'label' => trim($label),
            ));
        }
        return $options;
    }
    
    public function getShippingMethods($storeId = null)
    {
        $options = array();
        foreach(Mage::getSingleton('shipping/config')->getAllCarriers($storeId) as $code => $carrier)
        {
            if (!$name = Mage::getStoreConfig('carriers/'.$code.'/title')) {
                $name = $code;
            }

            array_unshift($options, array(
                'value' => $code,
                'label' => $name
            ));
        }
        return $options;
    }
}