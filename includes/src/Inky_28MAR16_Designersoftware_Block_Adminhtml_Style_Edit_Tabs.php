<?php

class Inky_Designersoftware_Block_Adminhtml_Style_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('style_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('designersoftware')->__('Style Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('designersoftware')->__('Style Information'),
          'title'     => Mage::helper('designersoftware')->__('Style Information'),
          'content'   => $this->getLayout()->createBlock('designersoftware/adminhtml_style_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}
