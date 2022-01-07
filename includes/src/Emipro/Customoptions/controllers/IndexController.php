<?php
class Emipro_Customoptions_IndexController extends Mage_Core_Controller_Front_Action {
	public function customoptionsAction() {		
		$modules = Mage::getConfig()->getNode('modules')->children();
		$modulesArray = (array)$modules;		
		if(isset($modulesArray['Emipro_Customoptions']) && $modulesArray['Emipro_Customoptions']->active=='true') {
				$this->getResponse()->setBody("true");
		} else {
			$this->getResponse()->setBody("false");
		}   
	}
}
