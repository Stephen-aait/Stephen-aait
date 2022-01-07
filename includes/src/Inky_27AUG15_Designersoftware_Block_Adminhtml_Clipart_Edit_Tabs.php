<?php

class Inky_Designersoftware_Block_Adminhtml_Clipart_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('clipart_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('designersoftware')->__('Clipart Information'));
  }

  protected function _beforeToHtml()
  {
      if( Mage::registry('clipart_update_attributes')){
		  $this->addTab('updateAttributes_section', array(
			  'label'     => Mage::helper('designersoftware')->__('Clipart Information'),
			  'title'     => Mage::helper('designersoftware')->__('Clipart Information'),
			  'content'   => $this->getLayout()->createBlock('designersoftware/adminhtml_clipart_edit_tab_updateattributes')->toHtml(),
		  ));
	  } else {
		  $this->addTab('form_section', array(
          'label'     => Mage::helper('designersoftware')->__('Clipart Information'),
          'title'     => Mage::helper('designersoftware')->__('Clipart Information'),
          'content'   => $this->getLayout()->createBlock('designersoftware/adminhtml_clipart_edit_tab_form')->toHtml(),
      ));
	  }
      
      
      /*$this->addTab('color_section', array(
         'label'     => Mage::helper('designersoftware')->__('Color Info'),
         'title'     => Mage::helper('designersoftware')->__('Color Info'),
         'url'       => $this->getUrl('//color', array('_current' => true)),
         'class'     => 'ajax',
      ));*/ 
     
      return parent::_beforeToHtml();
  }
}
