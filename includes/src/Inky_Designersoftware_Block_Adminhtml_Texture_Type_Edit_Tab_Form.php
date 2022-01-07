<?php

class Sparx_Designersoftware_Block_Adminhtml_Leathertype_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);     
      $fieldset = $form->addFieldset('leathertype_form', array('legend'=>Mage::helper('designersoftware')->__('Leathertype information')));
      
      if($this->getRequest()->getParam('id')):
		$disabled = true;
	  else:
		$disabled = false;
      endif;
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('designersoftware')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));
      
      $fieldset->addField('code', 'text', array(
          'label'     => Mage::helper('designersoftware')->__('Code'),
          'class'     => 'required-entry',
          'required'  => true,
          'disabled'  => $disabled,
          'name'      => 'code',
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
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getLeathertypeData() )
      {		  
          $form->setValues(Mage::getSingleton('adminhtml/session')->getLeathertypeData());
          Mage::getSingleton('adminhtml/session')->setLeathertypeData(null);
      } elseif ( Mage::registry('leathertype_data') ) {
          $form->setValues(Mage::registry('leathertype_data')->getData());         
      }
      
      return parent::_prepareForm();
  }
}
