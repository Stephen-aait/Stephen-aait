<?php

class Emipro_Customoptions_Block_Adminhtml_Customoptions extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct(){
	parent::__construct();
	$this->_controller = "adminhtml_customoptions";
	$this->_blockGroup = "emipro_customoptions";
	$this->_headerText = Mage::helper("emipro_customoptions")->__("Manage Custom Options");
	$this->_updateButton("add","label",$this->__("Add new option"));
	
	}

}
