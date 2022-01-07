<?php
class Netzkollektiv_InStorePickupPlus_Block_Onepage_Payment_Methods extends Mage_Checkout_Block_Onepage_Payment_Methods
{
    public function getMethods()
    {
        $methods = parent::getMethods();
        
        if ($availableMethods = $this->_getAvailableMethods()) {
            $methods = $this->_filterPaymentMethods($methods, $availableMethods);
        }
        
        return $methods;
    }
    
    protected function _getAvailableMethods() {
        $active = Mage::getStoreConfig('shipping/instorepickupplus/active');
        $config = @unserialize(Mage::getStoreConfig('shipping/instorepickupplus/dependencies'));

        if(!$active 
            || !is_array($config) 
            || !array_key_exists('shipping_method', $config) 
            || !array_key_exists('payment_method', $config)
        ) {
            return false;
        }

        $shippingMethod = Mage::getSingleton('checkout/session')
            ->getQuote()
            ->getShippingAddress()
            ->getShippingMethod();

        if(!$shippingMethod) {
            return false;
        }
            
        $shippingMethod = explode('_', $shippingMethod);
        
        $availableMethods = array();
        foreach($config['shipping_method'] as $key => $method)
        {
            if($method == $shippingMethod[0]) {
                $availableMethods[$config['payment_method'][$key]] = true;
            }
        }
        return $availableMethods;
    }
    
    
    protected function _filterPaymentMethods($methods, $availableMethods) {
        foreach ($methods as $key => $method) {
            if(!isset($availableMethods[$method->getCode()])) {
                unset($methods[$key]);
            }
        }
        return $methods;
    }
}