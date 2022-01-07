<?php
/**
 * Form tabs class file
 * 
 * @category    Clarion
 * @package     Clarion_Storelocator
 * @author      Clarion Magento Team
 */
class Clarion_Storelocator_Block_Adminhtml_Storelocator_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
 /*
  * constructor function
  */
  public function __construct()
  {
      parent::__construct();
      $this->setId('storelocator_tabs');
      $this->setDestElementId('edit_form'); // this should be same as the form id define in form tag class file
      $this->setTitle(Mage::helper('clarion_storelocator')->__('Store Information'));
  }
  
 /**
  * So in the _beforeToHtml() function we specified the actual form field's location. 
  * ie for the first tab, we want to create the file Form.php in the location 
  * Excellence/Employee/Block/Adminhtml/Employee/Edit/Tab and for the second tab 
  * we want to create Image.php in the location Excellence/Employee/Block/Adminhtml/Employee/Edit/Tab. 
  */
  protected function _beforeToHtml()
  {
      /**
       * $this->addTab function used to add as many tabs as you want in your form.
       */
      $this->addTab('form_section_general', array(
          'label'     => Mage::helper('clarion_storelocator')->__('General'),
          'title'     => Mage::helper('clarion_storelocator')->__('General'),
          'content'   => $this->getLayout()->createBlock('clarion_storelocator/adminhtml_storelocator_edit_tab_form')->toHtml(),
      ));
      
      $this->addTab('form_section_google_map', array(
          'label'     => Mage::helper('clarion_storelocator')->__('Google Map'),
          'title'     => Mage::helper('clarion_storelocator')->__('Google Map'),
          'content'   => $this->getLayout()->createBlock('clarion_storelocator/adminhtml_storelocator_edit_tab_googlemap')->toHtml(),
      ));
      
      $this->addTab('form_section_display', array(
          'label'     => Mage::helper('clarion_storelocator')->__('Display'),
          'title'     => Mage::helper('clarion_storelocator')->__('Display'),
          'content'   => $this->getLayout()->createBlock('clarion_storelocator/adminhtml_storelocator_edit_tab_display')->toHtml(),
      ));
      
      $this->addTab('form_section_description', array(
          'label'     => Mage::helper('clarion_storelocator')->__('Description'),
          'title'     => Mage::helper('clarion_storelocator')->__('Description'),
          'content'   => $this->getLayout()->createBlock('clarion_storelocator/adminhtml_storelocator_edit_tab_description')->toHtml(),
      ));
      
      return parent::_beforeToHtml();
  }
}