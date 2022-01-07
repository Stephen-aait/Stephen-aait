<?php

class Inky_Designersoftware_Block_Adminhtml_Designersoftware_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('designersoftware_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('designersoftware')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('designersoftware')->__('Item Information'),
          'title'     => Mage::helper('designersoftware')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('designersoftware/adminhtml_designersoftware_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}
