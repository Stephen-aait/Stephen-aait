<?php
class Inky_Designersoftware_SaveController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {   		
    	$JSON = Mage::helper('designersoftware/save_json')->load($this->getRequest()->getPost());		
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($JSON));				
    }       
}
