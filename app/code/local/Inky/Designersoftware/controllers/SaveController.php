<?php
class Inky_Designersoftware_SaveController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {		
		$data = $this->getRequest()->getPost(); 
		if( !empty($data['eventType']) ){
			$JSON = Mage::helper('designersoftware/save_json')->load($this->getRequest()->getPost());		
			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($JSON));
		} else {
			Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::helper('core/http')->getHttpReferer());	
		}				
    }
    
    public function previewAction()
    { 
		$data = $this->getRequest()->getPost(); 
		
    	$JSON = Mage::helper('designersoftware/save_preview')->load($this->getRequest()->getPost());		
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($JSON));				
    }       
}
