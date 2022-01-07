<?php

class Inky_Designersoftware_Block_Adminhtml_Texture_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'designersoftware';
        $this->_controller = 'adminhtml_texture';
        
        $this->_updateButton('save', 'label', Mage::helper('designersoftware')->__('Save Texture'));
        $this->_updateButton('delete', 'label', Mage::helper('designersoftware')->__('Delete Texture'));
		
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
        if( Mage::registry('texture_data') && Mage::registry('texture_data')->getId() ) {
            return Mage::helper('designersoftware')->__("Edit Texture '%s'", $this->htmlEscape(Mage::registry('texture_data')->getTitle()));
        } else {
            return Mage::helper('designersoftware')->__('Add Texture');
        }
    }
}
