<?php

class Inky_Designersoftware_Helper_Config extends Mage_Core_Helper_Abstract
{
	//============== Config from General Tab ==========================
	
	// Checks tool is enabled/disabled
	public function isLetteringEnable(){
		return true;
		return Mage::getStoreConfig('dsgeneral/general/enable',Mage::app()->getStore()); // 1 Yes, 0 No
	}
	
	// Checks Theme customization for tool is enabled
	public function isThemeEnable(){
		return true;
		return Mage::getStoreConfig('dsgeneral/theme/enable',Mage::app()->getStore()); // 1 Yes, 0 No
	}
	
	// Set Backgroud Color of a Strip for Title of a Tool
	public function titleBGColor(){
		return true;
		return Mage::getStoreConfig('dsgeneral/theme/titlebgcolor',Mage::app()->getStore());
	}
	
	// Set Backgroud Color of a Tool
	public function bGColor(){
		return true;
		return Mage::getStoreConfig('dsgeneral/theme/bgolor',Mage::app()->getStore());
	}
	
	// Set Color of Button and Links on Tool
	public function color(){
		return true;
		return Mage::getStoreConfig('dsgeneral/theme/color',Mage::app()->getStore());
	}
	
	// Set Color of Ruler on Tool
	public function ruler(){
		return true;
		return Mage::getStoreConfig('dsgeneral/theme/ruler',Mage::app()->getStore());
	}
	
	// Set Color for "save and continue" button on Tool
	public function saveColor(){
		return true;
		return Mage::getStoreConfig('dsgeneral/theme/savecolor',Mage::app()->getStore());
	}
	
	//=================== Config from Lettering Designer Tab ============================
	
	// Returns Title of Tool
	public function getTitle(){
		return true;
		return Mage::getStoreConfig('designersoftware/general/title',Mage::app()->getStore());
	}
	
	// Returns Default text for Tool set
	public function getDefaultText(){
		return true;
		return Mage::getStoreConfig('designersoftware/general/default_text',Mage::app()->getStore());
	}
	
	// Checks Font Style is enabled
	public function isFontEnable(){
		return true;
		return Mage::getStoreConfig('designersoftware/text/font',Mage::app()->getStore());
	}
	
	// Returns Default font for Tool set
	public function defaultFont(){
		return true;
		return Mage::getStoreConfig('designersoftware/text/defaultfont',Mage::app()->getStore());
	}
	
	// Returns Default Text Color for Tool set
	public function defaultTextColor(){
		return true;
		return Mage::getStoreConfig('designersoftware/text/defaulttextcolor',Mage::app()->getStore());
	}
	
	//=================== Config from My Design Tab ============================
	
	// Returns if Add to Cart option is Enable in My Design Section
	public function isAddToCartEnable(){
		return true;
		return Mage::getStoreConfig('mydesign/general/addtocart',Mage::app()->getStore());
	}
	
	// Returns if option to Remove design is Enable in My Design Section
	public function isRemoveEnable(){
		return true;
		return Mage::getStoreConfig('mydesign/general/remove',Mage::app()->getStore());
	}
}
