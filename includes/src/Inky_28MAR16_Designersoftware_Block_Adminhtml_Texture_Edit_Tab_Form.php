<?php

class Inky_Designersoftware_Block_Adminhtml_Texture_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('texture_form', array('legend'=>Mage::helper('designersoftware')->__('Texture information')));
     
      $fromData = Mage::registry('texture_data')->getData();
      
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
	  
	  $fieldset->addField('texture_code', 'text', array(
          'label'     => Mage::helper('designersoftware')->__('Code'),
          'class'     => 'required-entry',
          'disabled'  => $disabled,	
          'required'  => true,
          'name'      => 'texture_code',
      ));
      
      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('designersoftware')->__('File'),
          'required'  => false,
          'name'      => 'filename',          
          'after_element_html' => $fromData['filename']!=''?'<p><img src="' . Mage::helper('designersoftware/image')->_thumbWebPath(224,224) . $fromData['filename']. '"/></p>':'',
	  ));
	  
	  /*$fieldset->addField('texturetype_id', 'select', array(
            'label' => Mage::helper('designersoftware')->__('Type'),
            'name' => 'texturetype_id',
            'class' => 'required-entry',
            'required' => true,
            'values' => Mage::helper('designersoftware')->getLeathertype(),
      ));
      
      
      $fieldset->addField('texturecolor_id', 'select', array(
            'label' => Mage::helper('designersoftware')->__('Color'),
            'name' => 'texturecolor_id',
            'class' => 'required-entry',
            'required' => true,
            'values' => Mage::helper('designersoftware')->getLeathercolor(),
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
     
      if ( Mage::getSingleton('adminhtml/session')->getTextureData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getTextureData());
          Mage::getSingleton('adminhtml/session')->setTextureData(null);
      } elseif ( Mage::registry('texture_data') ) {
          $form->setValues(Mage::registry('texture_data')->getData());
      }
      return parent::_prepareForm();
  }
}
