<?php

class Inky_Designersoftware_Block_Adminhtml_Clipart_Upload_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('clipart_category_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('designersoftware')->__('Clipart Category Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('designersoftware')->__('Clipart Upload Information'),
          'title'     => Mage::helper('designersoftware')->__('Clipart Upload Information'),
          'content'   => $this->getLayout()->createBlock('designersoftware/adminhtml_clipart_upload_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}
