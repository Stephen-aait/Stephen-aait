<?php

class Inky_Designersoftware_Block_Adminhtml_Color_Edit_Tab_Clipart extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('color_form', array('legend'=>Mage::helper('designersoftware')->__('Clipart Information')));
	  	  
      $fieldset->addField('clip_price', 'text', array(
          'label'     => Mage::helper('designersoftware')->__('Clipart Color Price'),
          //'class'     => 'required-entry',
          //'required'  => true,
          'name'      => 'clip_price',          
      ));
      /*
      $fieldset->addField('code', 'text', array(
          'label'           => Mage::helper('color')->__('Color Code'),
          'class'           => 'required-entry color',
          'required'        => true,
          'name'            => 'code',
          'style'           => "width:150px;background-color:#".$colorPostData['code'],
          'disabled'  => 'disabled'
          //'after_element_html'=>'<script src='.$this->getJsUrl().'jscolor/jscolor.js'.'></script>'
      ));

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('color')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
     $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('color')->__('Status'),
          'name'      => 'status',
          'disabled'  => 'disabled',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('color')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('color')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('color')->__('Content'),
          'title'     => Mage::helper('color')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));*/
     
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
