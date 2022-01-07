<?php

class Inky_Designersoftware_Block_Adminhtml_Designersoftware_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('designersoftware_form', array('legend'=>Mage::helper('designersoftware')->__('Designersoftware info')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('designersoftware')->__('Title'),
          //'class'     => 'required-entry',
          //'required'  => true,
          'name'      => 'title',
      ));
      
   	  $getCustomer = Mage::helper('designersoftware/customer')->getAllCustomers();
   	  //echo '<pre>';var_dump($getCustomer);exit;
      $fieldset->addField('customer_id', 'select', array(
          'label'     => Mage::helper('designersoftware')->__('Customer'),
          //'class'     => 'required-entry',
          //'required'  => true,
          'name'      => 'customer_id',
          'values' => $getCustomer
      ));
      
      $fieldset->addField('sort_order', 'text', array(
          'label'     => Mage::helper('designersoftware')->__('Sort Order'),
          //'class'     => 'required-entry',
          //'required'  => true,
          'name'      => 'sort_order',
      ));

      /*$fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('designersoftware')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));*/
	  
	  $fieldset->addField('gallery_status', 'select', array(
          'label'     => Mage::helper('designersoftware')->__('Galley Status'),
          'name'      => 'gallery_status',
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
     
      /*$fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('designersoftware')->__('Content'),
          'title'     => Mage::helper('designersoftware')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));*/
     
      if ( Mage::getSingleton('adminhtml/session')->getDesignersoftwareData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getDesignersoftwareData());
          Mage::getSingleton('adminhtml/session')->setDesignersoftwareData(null);
      } elseif ( Mage::registry('designersoftware_data') ) {
          $form->setValues(Mage::registry('designersoftware_data')->getData());
      }
      return parent::_prepareForm();
  }
}
