<?php

class Inky_Designersoftware_Block_Adminhtml_Color_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('color_form', array('legend'=>Mage::helper('designersoftware')->__('Color information')));
     
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
	  
	  $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('designersoftware')->__('Content'),
          'title'     => Mage::helper('designersoftware')->__('Content'),
          'style'     => 'width:274px; height:93px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
	  */ 
	  
	  $fieldset->addField('colorcode', 'text', array(
          'label'           => Mage::helper('designersoftware')->__('Color Code'),
          'class'           => 'required-entry color',
          'required'        => true,
          'name'            => 'colorcode',
          'style'           => "width:150px;",
          'after_element_html'=>'<script src='.$this->getJsUrl().'jscolor/jscolor.js'.'></script>'
      ));
      
      
      $fieldset->addField('clip_status', 'select', array(
          'label'     => Mage::helper('designersoftware')->__('Clipart Status'),
          'name'      => 'clip_status',          
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
          'after_element_html'=> '<p class="note">select \'Enabled\' to set color and price for clipart.</p>',
      ));
      
       $fieldset->addField('text_status', 'select', array(
          'label'     => Mage::helper('designersoftware')->__('Text Status'),
          'name'      => 'text_status',          
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
          'after_element_html'=> '<p class="note">select \'Enabled\' to set color and price for text.</p>',
      ));
      	
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
     
      
     
      if ( Mage::getSingleton('adminhtml/session')->getColorData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getColorData());
          Mage::getSingleton('adminhtml/session')->setColorData(null);
      } elseif ( Mage::registry('color_data') ) {
          $form->setValues(Mage::registry('color_data')->getData());
      }
      return parent::_prepareForm();
  }
}
