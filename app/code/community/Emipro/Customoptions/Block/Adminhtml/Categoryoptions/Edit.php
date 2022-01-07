<?php

class Emipro_Customoptions_Block_Adminhtml_Categoryoptions_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {

        parent::__construct();
        $this->_objectId = "entity_id";
        $this->_blockGroup = "emipro_customoptions";
        $this->_controller = "adminhtml_categoryoptions";
        ;
        $this->_removeButton('delete');
        $this->_removeButton('save');
        $this->_removeButton('reset');
        $this->_addButton("saveandcontinue", array(
            "label" => Mage::helper("emipro_customoptions")->__("Save Option to Category"),
            "onclick" => "saveAndContinueEdit()",
            "class" => "save",
                ), -100);
        $this->_addButton("deletecategory", array(
            "label" => Mage::helper("emipro_customoptions")->__("Remove Option from Category"),
            "onclick" => "deleteCat()",
            "class" => "delete",
                ), -100);



        $this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');

							}
							function deleteCat(){
								editForm.submit($('edit_form').action+'del/delete/');

							}
						";
    }

    public function getHeaderText() {
	if(Mage::registry("option_data"))
	{$data=Mage::registry("option_data")->getData();}

        if ($data && $data[0]["option_id"]) {

            return Mage::helper("emipro_customoptions")->__("Assign/Remove '%s' option from category", $this->htmlEscape($data[0]["title"]));
        } else {

            return Mage::helper("emipro_customoptions")->__("Add new option");
        }
    }
   

}
