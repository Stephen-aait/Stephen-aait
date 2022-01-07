<?php
class Emipro_Customoptions_Block_Adminhtml_Removecustomoptions_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {

        parent::__construct();
        $this->_objectId = "entity_id";
        $this->_blockGroup = "emipro_customoptions";
        $this->_controller = "adminhtml_removecustomoptions";
        $this->_updateButton("save", "label", Mage::helper("emipro_customoptions")->__("Assign option to product(s)"));
        $this->removeButton("delete");
        $this->removeButton("reset");

        $this->_addButton("delete", array(
            "label" => Mage::helper("emipro_customoptions")->__("Remove option from product(s)"),
            "onclick" => "deleteOption()",
            "class" => "delete",
                ), -100);


        $this->_formScripts[] = "

							function deleteOption(){
								editForm.submit($('edit_form').action+'sku/delete/');

							}
						";
    }

    public function getHeaderText() {

	if(Mage::registry("option_data"))
	{$data=Mage::registry("option_data")->getData();}
        if ($data && $data[0]["option_id"]) {

            return Mage::helper("emipro_customoptions")->__("Assign/Remove '%s' option in/from product(s) [SKU list]", $this->htmlEscape($data[0]["title"]));
        } else {

            return Mage::helper("emipro_customoptions")->__("Add new option");
        }
    }

}
