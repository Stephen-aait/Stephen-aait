<?php
class Inky_Designersoftware_Parts_LayersController extends Mage_Core_Controller_Front_Action
{
    public function colorAction()
    {		
		$JSON = Mage::helper('designersoftware/parts_layers_color_json')->load($this->getRequest()->getPost());		
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($JSON));
    }       
}
