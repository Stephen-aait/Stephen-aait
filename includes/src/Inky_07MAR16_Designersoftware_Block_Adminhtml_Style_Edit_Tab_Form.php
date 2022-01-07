<?php

class Inky_Designersoftware_Block_Adminhtml_Style_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('style_form', array('legend'=>Mage::helper('designersoftware')->__('Style information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('designersoftware')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      /*
      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('designersoftware')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
	  */ 
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('designersoftware')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('designersoftware')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('designersoftware')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('designersoftware')->__('Content'),
          'title'     => Mage::helper('designersoftware')->__('Content'),
          'style'     => 'width:274px; height:93px;',
          'wysiwyg'   => false,
          'required'  => false,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getStyleData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getStyleData());
          Mage::getSingleton('adminhtml/session')->setStyleData(null);
      } elseif ( Mage::registry('style_data') ) {
          $form->setValues(Mage::registry('style_data')->getData());
      }
      return parent::_prepareForm();
  }
}
