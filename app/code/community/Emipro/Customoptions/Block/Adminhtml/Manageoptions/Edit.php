<?php
class Emipro_Customoptions_Block_Adminhtml_Manageoptions_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

	protected $addProductAction;

    public function __construct() {

        parent::__construct();
        $this->addProductAction=Mage::app()->getRequest()->getParam("sku");
        $this->_objectId = "entity_id";
        $this->_blockGroup = "emipro_customoptions";
        $this->_controller = "adminhtml_customoptions";
        $lbl="Save and Continue";
			$this->_removeButton("delete");
        $this->_addButton("saveandcontinue", array(
            "label" => Mage::helper("emipro_customoptions")->__($lbl),
            "onclick" => "saveAndContinueEdit()",
            "class" => "save",
                ), -100);

        $this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');

							}
						";


    }
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', array('_current'=>true));
    }

    public function getHeaderText() {
		
	if(Mage::registry("product_name"))
	{
		$data=Mage::registry("product_name");
	}
        if ($data) {
			
				$lbl="Assign/remove Custom Options for '%s'";
			
            return Mage::helper("emipro_customoptions")->__($lbl, $this->htmlEscape($data));
        }
        }
    }
