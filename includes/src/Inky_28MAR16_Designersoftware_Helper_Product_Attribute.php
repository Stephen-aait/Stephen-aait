<?php

class Inky_Designersoftware_Helper_Product_Attribute extends Mage_Core_Helper_Abstract
{
	public function getAttributeSetIdByName($attrSetName='Default'){		
		$attributeSetId = Mage::getModel('eav/entity_attribute_set')
				->load($attrSetName, 'attribute_set_name')
				->getAttributeSetId();
		
		return $attributeSetId;
	}	
}
