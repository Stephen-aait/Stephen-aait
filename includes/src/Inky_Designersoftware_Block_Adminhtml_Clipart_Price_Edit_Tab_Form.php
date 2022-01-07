<?php

class Inky_Designersoftware_Block_Adminhtml_Clipart_Price_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('clipart_price_form', array('legend'=>Mage::helper('designersoftware')->__('Clipart Price information')));
     
      $fieldset->addField('width', 'text', array(
          'label'     => Mage::helper('designersoftware')->__('Width'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'width',
      ));
      
      
      $fieldset->addField('height', 'text', array(
          'label'     => Mage::helper('designersoftware')->__('Height'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'height',
      ));
      
      
      $fieldset->addField('width_price', 'text', array(
          'label'     => Mage::helper('designersoftware')->__('Width Price'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'width_price',
      ));
      
      $fieldset->addField('height_price', 'text', array(
          'label'     => Mage::helper('designersoftware')->__('Height Price'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'height_price',
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
          'required'  => false,
      ));
     */
     
      if ( Mage::getSingleton('adminhtml/session')->getClipartPriceData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getClipartPriceData());
          Mage::getSingleton('adminhtml/session')->setClipartPriceData(null);
      } elseif ( Mage::registry('clipart_price_data') ) {
          $form->setValues(Mage::registry('clipart_price_data')->getData());
      }
      return parent::_prepareForm();
  }
}
