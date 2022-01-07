<?php

class Inky_Designersoftware_Block_Adminhtml_Text_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('text_form', array('legend'=>Mage::helper('designersoftware')->__('Text Pricing Info')));
	  $fromData = Mage::registry('text_data')->getData();
	  
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('designersoftware')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));
      
      $fieldset->addField('cost', 'text', array(
          'label'     => Mage::helper('designersoftware')->__('Cost Per Character'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'cost',
      ));
      
      $fieldset->addField('min_price', 'text', array(
          'label'     => Mage::helper('designersoftware')->__('Minimum Price'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'min_price',
      ));
      
      $fieldset->addField('max_char', 'text', array(
          'label'     => Mage::helper('designersoftware')->__('Maximum Characters'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'max_char',
      ));
      
      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('designersoftware')->__('Image'),
          'required'  => false,
          'name'      => 'filename',
          'after_element_html' => $fromData['filename']!=''?'<p><img src="' . Mage::helper('designersoftware/image')->_thumbWebPath(230,164) . $fromData['filename']. '"/></p>':'',
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
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('designersoftware')->__('Content'),
          'title'     => Mage::helper('designersoftware')->__('Content'),
          'style'     => 'width:274px; height:93px;',
          'wysiwyg'   => false,
          'required'  => false,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getTextData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getTextData());
          Mage::getSingleton('adminhtml/session')->setTextData(null);
      } elseif ( Mage::registry('text_data') ) {
          $form->setValues(Mage::registry('text_data')->getData());
      }
      return parent::_prepareForm();
  }
}
