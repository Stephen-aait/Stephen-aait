<?php

class Inky_Designersoftware_Block_Adminhtml_Text_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('text_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('designersoftware')->__('Text Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('designersoftware')->__('Text Information'),
          'title'     => Mage::helper('designersoftware')->__('Text Information'),
          'content'   => $this->getLayout()->createBlock('designersoftware/adminhtml_text_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}
