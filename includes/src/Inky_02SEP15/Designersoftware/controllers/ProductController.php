<?php
class Inky_Designersoftware_ProductController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	$data = $this->getRequest()->getParams();
    			
    	$JSON = Mage::helper('designersoftware/product_json')->load($this->getRequest()->getPost());		
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($JSON));
    }         
}
