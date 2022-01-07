<?php
class Inky_Designersoftware_PartsController extends Mage_Core_Controller_Front_Action
{
    public function styleAction()
    {		
		$JSON = Mage::helper('designersoftware/parts_style_json')->load($this->getRequest()->getPost());		
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($JSON));
    } 
    
    public function layersAction()
    {		
		$JSON = Mage::helper('designersoftware/parts_layers_json')->load($this->getRequest()->getPost());		
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($JSON));
    }       
}
