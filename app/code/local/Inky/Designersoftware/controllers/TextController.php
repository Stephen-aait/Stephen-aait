<?php
class Inky_Designersoftware_TextController extends Mage_Core_Controller_Front_Action
{  
	public function IndexAction()
    {   	
		$JSON = Mage::helper('designersoftware/text_json')->load($this->getRequest()->getParams());		
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($JSON));
    } 
      
    public function ColorAction()
    {   	
		$JSON = Mage::helper('designersoftware/text_color_json')->load($this->getRequest()->getParams());		
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($JSON));
    }    
}
