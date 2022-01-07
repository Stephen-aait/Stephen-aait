<?php

class Inky_Designersoftware_Block_Adminhtml_Font_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'designersoftware';
        $this->_controller = 'adminhtml_font';
        
        $this->_updateButton('save', 'label', Mage::helper('designersoftware')->__('Save Font'));
        $this->_updateButton('delete', 'label', Mage::helper('designersoftware')->__('Delete Font'));
		
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
            
             function valImgExt(obj,IMAGE_TYPE){
                    var val = myJquery(obj).val();
                    //alert(val);
                    switch(val.substring(val.lastIndexOf('.') + 1).toLowerCase()){
                        case IMAGE_TYPE:
                           if(IMAGE_TYPE == 'ttf' || IMAGE_TYPE == 'swf' ){
                                var ddlId = myJquery(obj).attr('id');
                                var last_ = ddlId.split('_');  
                                last_     = last_[1];
                                //givingNameInOrder();
                                if(last_ != 1){
                                    //alert('ok');
                                    // return false;
                                }  
                            }
                            return true;
                            break;
                        default:
                           myJquery(obj).val('');
                            // error message here
                            alert('Only '+IMAGE_TYPE.toUpperCase()+' File !!!');
                            return false;
                            break;
                    }
                } 
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('font_data') && Mage::registry('font_data')->getId() ) {
            return Mage::helper('designersoftware')->__("Edit Font '%s'", $this->htmlEscape(Mage::registry('font_data')->getTitle()));
        } else {
            return Mage::helper('designersoftware')->__('Add Font');
        }
    }
}
