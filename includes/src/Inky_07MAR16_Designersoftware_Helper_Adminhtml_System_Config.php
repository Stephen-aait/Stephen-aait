<?php

class Inky_Designersoftware_Helper_Adminhtml_System_Config extends Mage_Core_Helper_Abstract
{
	public function getAngle(){		
		return Mage::getStoreConfig('designeradmin/general/angle');
	}	
}
