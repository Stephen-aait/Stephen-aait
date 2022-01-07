<?php
class Inky_Designersoftware_ClipartController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {    		
		 /*Mage::dispatchEvent('angles_cart_add_product_complete',
                array('product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse())
           );*/           
		$JSON = Mage::helper('designersoftware/clipart_json')->load($this->getRequest()->getParams());		
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($JSON));
    } 
    
    public function categoryAction()
    {   	
		$JSON = Mage::helper('designersoftware/clipart_category_json')->load($this->getRequest()->getParams());		
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($JSON));
    }   
    
    
    public function colorAction()
    {   	
		$JSON = Mage::helper('designersoftware/clipart_color_json')->load($this->getRequest()->getParams());		
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($JSON));
    }    
}
