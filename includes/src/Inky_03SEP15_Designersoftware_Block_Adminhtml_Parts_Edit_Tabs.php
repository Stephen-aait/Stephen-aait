<?php

class Inky_Designersoftware_Block_Adminhtml_Parts_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('leaher_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('designersoftware')->__('Parts Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('designersoftware')->__('Parts Information'),
          'title'     => Mage::helper('designersoftware')->__('Parts Information'),
          'content'   => $this->getLayout()->createBlock('designersoftware/adminhtml_parts_edit_tab_form')->toHtml(),
      ));      
     
      return parent::_beforeToHtml();
  }
}
