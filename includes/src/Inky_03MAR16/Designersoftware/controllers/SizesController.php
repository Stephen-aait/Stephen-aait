<?php
class Inky_Designersoftware_SizesController extends Mage_Core_Controller_Front_Action
{	
    public function indexAction()
    {	
		$JSON = Mage::helper('designersoftware/sizes_json')->load($this->getRequest()->getParams());		
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($JSON));		
    } 
    
    public function colorAction()
    {   	
		$JSON = Mage::helper('designersoftware/sizes_color_json')->load($this->getRequest()->getParams());		
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($JSON));
    }       
}
