<?php

class Inky_Designersoftware_Block_Adminhtml_Parts_Layers_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('style_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('designersoftware')->__('Parts Layers Information'));
  }

  protected function _beforeToHtml()
  {
      if( Mage::registry('parts_layers_update_attributes')){		  
		  $this->addTab('updateAttributes_section', array(
			  'label'     => Mage::helper('designersoftware')->__('Parts Layers Info'),
			  'title'     => Mage::helper('designersoftware')->__('Parts Layers Info'),
			  'content'   => $this->getLayout()->createBlock('designersoftware/adminhtml_parts_layers_edit_tab_updateattributes')->toHtml(),
		  ));
		  
		  $this->addTab('color_section', array(
			 'label'     => Mage::helper('designersoftware')->__('Color Info'),
			 'title'     => Mage::helper('designersoftware')->__('Color Info'),
			 'url'       => $this->getUrl('*/*/color', array('_current' => true)),
			 'class'     => 'ajax',
		  )); 
		  
	  } else {
		  $this->addTab('form_section', array(
			  'label'     => Mage::helper('designersoftware')->__('General'),
			  'title'     => Mage::helper('designersoftware')->__('General'),
			  'content'   => $this->getLayout()->createBlock('designersoftware/adminhtml_parts_layers_edit_tab_form')->toHtml(),
		  )); 
		  
		  $this->addTab('layer_image_section', array(
			  'label'     => Mage::helper('designersoftware')->__('Layer Images'),
			  'title'     => Mage::helper('designersoftware')->__('Layer Images'),
			  'content'   => $this->getLayout()->createBlock('designersoftware/adminhtml_parts_layers_edit_tab_layer_image')->toHtml(),
		  )); 
		  
		  $this->addTab('color_section', array(
			 'label'     => Mage::helper('designersoftware')->__('Color Info'),
			 'title'     => Mage::helper('designersoftware')->__('Color Info'),
			 'url'       => $this->getUrl('*/*/color', array('_current' => true)),
			 'class'     => 'ajax',
		  ));      
	  }
      
      /*$this->addTab('texture_section', array(
          'label'     => Mage::helper('designersoftware')->__('Texture Info'),
          'title'     => Mage::helper('designersoftware')->__('Texture Info'),
          'url'       => $this->getUrl('//texture', array('_current' => true)),
          'class'     => 'ajax',
      ));*/   
      
      return parent::_beforeToHtml();
  }
}
