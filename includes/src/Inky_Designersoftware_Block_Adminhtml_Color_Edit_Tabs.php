<?php

class Inky_Designersoftware_Block_Adminhtml_Color_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('color_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('designersoftware')->__('Color Information'));
  }

  protected function _beforeToHtml()
  {
      if( Mage::registry('color_update_attributes')){
		  $this->addTab('updateAttributes_section', array(
			  'label'     => Mage::helper('designersoftware')->__('Color Information'),
			  'title'     => Mage::helper('designersoftware')->__('Color Information'),
			  'content'   => $this->getLayout()->createBlock('designersoftware/adminhtml_color_edit_tab_updateattributes')->toHtml(),
		  ));
	  } else {
		  $this->addTab('form_section', array(
			  'label'     => Mage::helper('designersoftware')->__('Color Information'),
			  'title'     => Mage::helper('designersoftware')->__('Color Information'),
			  'content'   => $this->getLayout()->createBlock('designersoftware/adminhtml_color_edit_tab_form')->toHtml(),
		  ));
		  
		  if(Mage::getModel('designersoftware/color')->isClipEnable($this->getRequest()->getParams('id'))==1){      
			  $this->addTab('clip_section', array(
				  'label'     => Mage::helper('designersoftware')->__('Clipart Info'),
				  'title'     => Mage::helper('designersoftware')->__('Clipart Info'),
				  'content'   => $this->getLayout()->createBlock('designersoftware/adminhtml_color_edit_tab_clipart')->toHtml(),
			  ));
		  }
		  
		  if(Mage::getModel('designersoftware/color')->isTextEnable($this->getRequest()->getParams('id'))==1){      
			  $this->addTab('text_section', array(
				  'label'     => Mage::helper('designersoftware')->__('Text Info'),
				  'title'     => Mage::helper('designersoftware')->__('Text Info'),
				  'content'   => $this->getLayout()->createBlock('designersoftware/adminhtml_color_edit_tab_text')->toHtml(),
			  ));
		  }
	  }
      
      
      
     
      return parent::_beforeToHtml();
  }
}
