<?php

class Inky_Designersoftware_Block_Adminhtml_Clipart_Upload_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('clipart_upload_form', array('legend'=>Mage::helper('designersoftware')->__('Clipart Upload information')));
	  
	  $fromData = Mage::registry('clipart_upload_data')->getData();
	   	
      $allCustomers = Mage::helper('designersoftware/customer')->getAllCustomers();
      $fieldset->addField('customer_id', 'select', array(
          'label'     => Mage::helper('designersoftware')->__('Customer'),
          'class'     => 'required-entry',
          'required'  => true,
          //'disabled'  => $disabled,
          'name'      => 'customer_id',
          'values' 	  => $allCustomers,          
      ));	
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('designersoftware')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));
     
      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('designersoftware')->__('File'),
          'required'  => false,
          'name'      => 'filename',
          'after_element_html' => $fromData['filename']!=''?'<a href="'. Mage::helper('designersoftware/image')->_originalWebPath() . 'original'. DS . $fromData['filename'].'" target="_blank"><p><img src="' . Mage::helper('designersoftware/image')->_thumbWebPath(224,224) . $fromData['filename']. '"/></p><br><a href="'. Mage::helper('designersoftware/image')->_originalWebPath() . 'original'. DS . $fromData['filename'].'" target="_blank">Download Clipart</a>':'',
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
     
      if ( Mage::getSingleton('adminhtml/session')->getClipartUploadData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getClipartUploadData());
          Mage::getSingleton('adminhtml/session')->setClipartUploadData(null);
      } elseif ( Mage::registry('clipart_upload_data') ) {
          $form->setValues(Mage::registry('clipart_upload_data')->getData());
      }
      return parent::_prepareForm();
  }
}
