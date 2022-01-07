<?php 
class Emipro_Customoptions_Block_Adminhtml_Manageoptions extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct(){
	parent::__construct();
	$this->_controller = "adminhtml_manageoptions";
	$this->_blockGroup = "emipro_customoptions";
	$this->_headerText = Mage::helper("emipro_customoptions")->__("Select Product to assign/remove custom options");
	$this->_removeButton("add");
	}
}
