<?php

class Emipro_Customoptions_Block_Adminhtml_Assigntoproduct_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct()
	{
		parent::__construct();
		$this->_blockGroup = 'emipro_customoptions';
		$this->_controller = 'adminhtml_assigntoproduct';
		$this->_mode='edit';
		$this->_updateButton("save", "label", Mage::helper("emipro_customoptions")->__("Assign options to product(s)"));
		
		$this->_addButton("delete", array(
            "label" => Mage::helper("emipro_customoptions")->__("Remove options from product(s)"),
            "onclick" => "deleteOption()",
            "class" => "delete",
                ), -100);

        $this->_formScripts[] = "function deleteOption(){editForm.submit($('edit_form').action+'sku/delete/');}";
        $this->_removeButton('back');
	}
 
	public function getHeaderText()
	{
		return Mage::helper('emipro_customoptions')->__('Assign/remove multiple custom options to product(s)');
	}
}
