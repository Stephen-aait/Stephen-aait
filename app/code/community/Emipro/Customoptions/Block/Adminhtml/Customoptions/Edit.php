<?php
class Emipro_Customoptions_Block_Adminhtml_Customoptions_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

	protected $addProductAction;

    public function __construct() {

        parent::__construct();
        $this->addProductAction=Mage::app()->getRequest()->getParam("sku");
        $this->_objectId = "entity_id";
        $this->_blockGroup = "emipro_customoptions";
        $this->_controller = "adminhtml_customoptions";
        $this->_updateButton("delete","onclick",'confirmSetLocation(\''
                                . Mage::helper('catalog')->__('If you delete custom option, it will remove from all product(s). Are you sure to delete this custom option ?').'\', \''.$this->getDeleteUrl().'\')');		
        $lbl="Save and Continue";
		if($this->addProductAction)
		{
			$this->_removeButton("delete");
		}
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
		
	if(Mage::registry("option_data"))
	{
		$data=Mage::registry("option_data")->getData();
	}
        if ($data && $data[0]["option_id"]) {
			$lbl="Edit option '%s'";
			if($this->addProductAction)
			{
				$lbl="Assign '%s' option in product(s)";
			}	
            return Mage::helper("emipro_customoptions")->__($lbl, $this->htmlEscape($data[0]["title"]));
        } else {

            return Mage::helper("emipro_customoptions")->__("Add new option");
        }
    }

}
