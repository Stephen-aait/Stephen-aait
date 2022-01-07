<?php

class Inky_Designersoftware_Block_Adminhtml_Style_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'designersoftware';
        $this->_controller = 'adminhtml_style';
        
        $this->_updateButton('save', 'label', Mage::helper('designersoftware')->__('Save Style'));
        $this->_updateButton('delete', 'label', Mage::helper('designersoftware')->__('Delete Style'));
		
		$this->_removeButton('delete');
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('designersoftware_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'designersoftware_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'designersoftware_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('style_data') && Mage::registry('style_data')->getId() ) {
            return Mage::helper('designersoftware')->__("Edit Style '%s'", $this->htmlEscape(Mage::registry('style_data')->getTitle()));
        } else {
            return Mage::helper('designersoftware')->__('Add Style');
        }
    }
}
