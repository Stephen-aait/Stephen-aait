<?php
class Inky_Designersoftware_TestController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	$productCollection = Mage::getModel('catalog/product')->load(890);
    	echo '<pre>';print_r($productCollection->getUrlPath());   	
    }  
	
    public function thumbAction(){			
		Mage::helper('designersoftware/composite_image')->getImage($data);	
	}   
}
