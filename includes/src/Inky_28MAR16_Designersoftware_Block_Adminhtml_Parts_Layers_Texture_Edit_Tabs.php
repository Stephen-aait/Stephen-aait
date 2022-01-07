<?php

class Inky_Designersoftware_Block_Adminhtml_Parts_Layers_Texture_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('parts_layers_texture_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('designersoftware')->__('Texture Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('designersoftware')->__('Texture Information'),
          'title'     => Mage::helper('designersoftware')->__('Texture Information'),
          'content'   => $this->getLayout()->createBlock('designersoftware/adminhtml_parts_layers_texture_edit_tab_form')->toHtml(),
      ));
      
      $this->addTab('layer_image_section', array(
          'label'     => Mage::helper('designersoftware')->__('Texture Layer Images'),
          'title'     => Mage::helper('designersoftware')->__('Texture Layer Images'),
          'content'   => $this->getLayout()->createBlock('designersoftware/adminhtml_parts_layers_texture_edit_tab_layer_image')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}
