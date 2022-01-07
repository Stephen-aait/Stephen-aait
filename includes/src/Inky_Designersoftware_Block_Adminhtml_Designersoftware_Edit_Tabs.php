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
	  if( Mage::registry('designersoftware_update_attributes')){	
		  $this->addTab('updateAttributes_section', array(
			  'label'     => Mage::helper('designersoftware')->__('Update Designs Info'),
			  'title'     => Mage::helper('designersoftware')->__('Update Designs Info'),
			  'content'   => $this->getLayout()->createBlock('designersoftware/adminhtml_designersoftware_edit_tab_updateattributes')->toHtml(),
		  ));	
		  
	  } else {
		  $this->addTab('form_section', array(
			  'label'     => Mage::helper('designersoftware')->__('Designersoftware Info'),
			  'title'     => Mage::helper('designersoftware')->__('Designersoftware Info'),
			  'content'   => $this->getLayout()->createBlock('designersoftware/adminhtml_designersoftware_edit_tab_form')->toHtml(),
		  ));
	  }
     
      return parent::_beforeToHtml();
  }
}
