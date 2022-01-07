<?php
/**
 * Store locator form container
 * 
 * @category    Clarion
 * @package     Clarion_Storelocator
 * @author      Clarion Magento Team
 */
class Clarion_Storelocator_Block_Adminhtml_Storelocator_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        /**
         * This variable is used in the form URL’s. 
         * This variable has the forms entity primary key, e.g the delete button URL would be 
         * module/controller/action/$this->_objectid/3
         */
        $this->_objectId = 'storelocator_id';
        
        /*
         * There two variables are very important, these variables are used to find FORM tags php file.
         * i.e the path of the form tags php file should be 
         * {$this->_blockGroup . ‘/’ . $this->_controller . ‘_’ . $this->_mode . ‘_form’}.
         * The value of $this->_mode by default is ‘edit’. So the path of the php file which contains
         *  the form tag in our case would be ‘clarion_storelocator/adminhtml_storelocator_edit_form’. 
         */
        $this->_blockGroup = 'clarion_storelocator';
        $this->_controller = 'adminhtml_storelocator';
        
        parent::__construct();
         
         /**
         * $this->_updateButton() and $this->_addButton() are used to add update/add buttons to the form container. 
         */
        $this->_updateButton('save', 'label', Mage::helper('clarion_storelocator')->__('Save Store'));
        $this->_updateButton('delete', 'label', Mage::helper('clarion_storelocator')->__('Delete Store'));
         
        $this->_addButton(
            'saveandcontinue', 
            array(
                'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
                'onclick'   => 'saveAndContinueEdit()',
                'class'     => 'save',
            ), 
            100
         );
        
        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }
 
    /**
     * This function return’s the Text to display as the form header.
     */
    public function getHeaderText()
    {
        if (Mage::registry('storelocator_data')->getId()) {
            return Mage::helper('clarion_storelocator')->__("Edit Store '%s'", $this->escapeHtml(Mage::registry('storelocator_data')->getName()));
        }
        else {
            return Mage::helper('clarion_storelocator')->__('New Store');
        }
    }
    
}