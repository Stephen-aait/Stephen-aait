<?php

class Inky_Designersoftware_Helper_Customoption extends Mage_Core_Helper_Abstract
{
	// Starts, get selected options details
    public function sendCustomOptionUrl($productId, $designCodeValue){
		$customOptions = '';
        $product = Mage::getModel('catalog/product')->load($productId);
        foreach ($product->getOptions() as $o) {
            if((strtolower($o->getTitle())=='design code') && $o->getType()=='field'){
				    //$designCodeValue=strtoupper(uniqid('D-')).rand(1,1000);
				    //$designCodeValue=substr(uniqid(''),0,4).'-'.substr(uniqid(''),3,4).'-'.substr(uniqid(''),7,4);
					$customOptions.='&options['.$o->getId().']=' .$designCodeValue;
				}
        }
                 
        return $customOptions;
	}
	
	public function sendCustomOptionArray($productId,$designCodeValue){
		$customOptions = '';
        $product = Mage::getModel('catalog/product')->load($productId);
        foreach ($product->getOptions() as $o) {
            if((strtolower($o->getTitle())=='design code') && $o->getType()=='field'){
				    //$designCodeValue=strtoupper(uniqid('D-')).rand(1,1000);
					$customOptions['id']=$o->getId();
					$customOptions['value']=$designCodeValue;
				}
        }
         //$array['customOptions'] 	= $customOptions;
         //$array['designCodeValue'] 	= $designCodeValue;
         
        return $customOptions;
	}
	
    public function setDesignCodeCustomOption($productId){
        $options = array(
            array(
                'title' => 'Design code',
                'type' => 'field',
                'is_require' => 0,
                'sort_order' => 1,

            )
        );
        $product = Mage::getModel('catalog/product')->load($productId);
        $product->setHasOptions(true)->save();

        foreach($options as $option_data){
            $option = Mage::getModel('catalog/product_option')
                ->setProductId($productId)
                ->setStoreId($product->getStoreId())
                ->addData($option_data);

            $value = Mage::getModel('catalog/product_option_value');
            $value->setOption($option);
            $option->addValue($value);

            $option->save();
            $product->addOption($option);
            $product->save();
        }
    }	
}
