<?php

class Inky_Designersoftware_Block_Adminhtml_Clipart_Price_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('clipart_price_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('designersoftware')->__('Clipart Price Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('designersoftware')->__('Clipart Price Information'),
          'title'     => Mage::helper('designersoftware')->__('Clipart Price Information'),
          'content'   => $this->getLayout()->createBlock('designersoftware/adminhtml_clipart_price_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}
