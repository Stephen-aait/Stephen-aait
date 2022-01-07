<?php

class Inky_Designersoftware_Block_Adminhtml_Color_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'designersoftware';
        $this->_controller = 'adminhtml_color';
        
        $this->_updateButton('save', 'label', Mage::helper('designersoftware')->__('Save Color'));
        $this->_updateButton('delete', 'label', Mage::helper('designersoftware')->__('Delete Color'));
		
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
        if( Mage::registry('color_data') && Mage::registry('color_data')->getId() ) {
            return Mage::helper('designersoftware')->__("Edit Color '%s'", $this->htmlEscape(Mage::registry('color_data')->getTitle()));
        } else if(Mage::registry('color_data') && Mage::registry('color_data')->getUpdateColorIds()){
			return Mage::helper('designersoftware')->__('Update Color');
		} else {
            return Mage::helper('designersoftware')->__('Add Color');
        }
    }
}
