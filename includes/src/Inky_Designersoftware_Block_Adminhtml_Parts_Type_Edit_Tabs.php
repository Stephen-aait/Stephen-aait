<?php

class Sparx_Designersoftware_Block_Adminhtml_Parts_Type_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('parts_type_tab');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('designersoftware')->__('Parts Type Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('designersoftware')->__('Parts Type Information'),
          'title'     => Mage::helper('designersoftware')->__('Parts Type Information'),
          'content'   => $this->getLayout()->createBlock('designersoftware/adminhtml_parts_type_edit_tab_form')->toHtml(),
      ));     
      
      return parent::_beforeToHtml();
  }
}
