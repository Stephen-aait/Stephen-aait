<?php

class Sparx_Designersoftware_Block_Adminhtml_Parts_Type_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'designersoftware';
        $this->_controller = 'adminhtml_parts_type';
        
        $this->_updateButton('save', 'label', Mage::helper('designersoftware')->__('Save Parts Type'));
        $this->_updateButton('delete', 'label', Mage::helper('designersoftware')->__('Delete Parts Type'));
		
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
            
            
            function styleDesignlist(obj) {
                var val = myJquery(obj).val();                                
                //var value = '';
                var value = 'VGHB-2';                
                myJquery('#style_design_ids').append('<option value=\"'+ value +'\">' + value + '</option>');  
                myJquery('#style_design_ids').multiselect('refresh');                              
            }
            
            myJquery(document).ready(function(){
			   myJquery('select[name^=\'parts_type_id\']').attr('disabled','disabled');
               myJquery('select[name^=\'parts_type_id\']').parent().parent().hide(); 
               
               myJquery('select[name=\'parts_type_id['+ val +']\']').removeAttr('disabled');
               myJquery('select[name=\'parts_type_id['+ val +']\']').parent().parent().show();
               
		    });
		    
            function selectPartsType(obj){
				var val = myJquery(obj).val();
				myJquery('select[name^=\'parts_type_id\']').attr('disabled','disabled');
				myJquery('select[name^=\'parts_type_id\']').parent().parent().hide();				
				
				myJquery('select[name^=\'parts_type_id['+val+']\']').removeAttr('disabled');
                myJquery('select[name=\'parts_type_id['+val+']\']').parent().parent().show();				
			} 
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('parts_type_data') && Mage::registry('parts_type_data')->getId() ) {
            return Mage::helper('designersoftware')->__("Edit Parts Type '%s'", $this->htmlEscape(Mage::registry('parts_type_data')->getTitle()));
        } else {
            return Mage::helper('designersoftware')->__('Add Parts Type');
        }
    }
}
