<?php

class Inky_Designersoftware_Block_Adminhtml_Sizes_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('leaher_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('designersoftware')->__('Sizes Information'));
  }

  protected function _beforeToHtml()
  {      
     if( Mage::registry('sizes_update_attributes')){
		  $this->addTab('updateAttributes_section', array(
			  'label'     => Mage::helper('designersoftware')->__('Sizes Information'),
			  'title'     => Mage::helper('designersoftware')->__('Sizes Information'),
			  'content'   => $this->getLayout()->createBlock('designersoftware/adminhtml_sizes_edit_tab_updateattributes')->toHtml(),
		  ));
		  
		  $this->addTab('color_section', array(
			 'label'     => Mage::helper('designersoftware')->__('Color Info'),
			 'title'     => Mage::helper('designersoftware')->__('Color Info'),
			 'url'       => $this->getUrl('*/*/color', array('_current' => true)),
			 'class'     => 'ajax',
		  )); 
		  
	  } else {
		  $this->addTab('form_section', array(
			'label'     => Mage::helper('designersoftware')->__('Sizes Information'),
			'title'     => Mage::helper('designersoftware')->__('Sizes Information'),
			'content'   => $this->getLayout()->createBlock('designersoftware/adminhtml_sizes_edit_tab_form')->toHtml(),
		  ));
		  
		 $this->addTab('color_section', array(
			'label'     => Mage::helper('designersoftware')->__('Color Info'),
			'title'     => Mage::helper('designersoftware')->__('Color Info'),
			'url'       => $this->getUrl('*/*/color', array('_current' => true)),
			'class'     => 'ajax',
		 ));
	  }
	  
      return parent::_beforeToHtml();
  }
}
