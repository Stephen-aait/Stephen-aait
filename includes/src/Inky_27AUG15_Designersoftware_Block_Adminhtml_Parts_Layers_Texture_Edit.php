<?php

class Inky_Designersoftware_Block_Adminhtml_Parts_Layers_Texture_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'designersoftware';
        $this->_controller = 'adminhtml_parts_layers_texture';
        
        $this->_updateButton('save', 'label', Mage::helper('designersoftware')->__('Save Texture'));
        $this->_updateButton('delete', 'label', Mage::helper('designersoftware')->__('Delete Texture'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);
		
		$parts_id = Mage::registry('parts_layers_texture_data')->getData('parts_id');
		$parts_layers_id = Mage::registry('parts_layers_texture_data')->getData('parts_layers_id');
		$texture_id = Mage::registry('parts_layers_texture_data')->getData('texture_id');
		
		if(empty($parts_layers_id) && empty($parts_id) && empty($texture_id)):
			$parts_id = 1; 
			$parts_layers_id = 1;
			$texture_id = 1;
		endif;
		
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
            
            /*myJquery(document).ready(function(){
			   //	alert($parts_layers_id);
			   myJquery('select[id^=\'parts_layers_id\']').attr('disabled','disabled');
               myJquery('select[id^=\'parts_layers_id\']').parent().parent().hide(); 
               
               myJquery('select[id=\'parts_layers_id[' + $parts_id +']\']').removeAttr('disabled');
               myJquery('select[id=\'parts_layers_id[' + $parts_id +']\']').parent().parent().show();
               
               myJquery('select[id=\'parts_layers_id[' + $parts_id +']\']').val($parts_layers_id);
               
               
               //TEXTURE CHECK
               
               
               myJquery('select[id^=\'texture_id\']').attr('disabled','disabled');
               myJquery('select[id^=\'texture_id\']').parent().parent().hide(); 
               
               myJquery('select[id=\'texture_id[' + $parts_layers_id +']\']').removeAttr('disabled');
               myJquery('select[id=\'texture_id[' + $parts_layers_id +']\']').parent().parent().show();
               
               myJquery('select[id=\'texture_id[' + $parts_layers_id +']\']').val($texture_id);
               
		    });
            
            function selectPartsLayers(obj){
				var val = myJquery(obj).val();
				
				myJquery('select[id^=\'parts_layers_id\']').attr('disabled','disabled');
				myJquery('select[id^=\'parts_layers_id\']').parent().parent().hide();				
				
				myJquery('select[id^=\'parts_layers_id['+val+']\']').removeAttr('disabled');
                myJquery('select[id=\'parts_layers_id['+val+']\']').parent().parent().show();				
			} 
			
			function selectTexture(obj){
				var val = myJquery(obj).val();
				
				myJquery('select[id^=\'texture_id\']').attr('disabled','disabled');
				myJquery('select[id^=\'texture_id\']').parent().parent().hide();				
				
				myJquery('select[id^=\'texture_id['+val+']\']').removeAttr('disabled');
                myJquery('select[id=\'texture_id['+val+']\']').parent().parent().show();				
			}*/
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('parts_layers_texture_data') && Mage::registry('parts_layers_texture_data')->getId() ) {
            return Mage::helper('designersoftware')->__("Edit Texture '%s'", $this->htmlEscape(Mage::registry('parts_layers_texture_data')->getTitle()));
        } else {
            return Mage::helper('designersoftware')->__('Add Texture');
        }
    }
}
