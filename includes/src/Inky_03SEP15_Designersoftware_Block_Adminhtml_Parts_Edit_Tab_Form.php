<?php

class Inky_Designersoftware_Block_Adminhtml_Parts_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('parts_form', array('legend'=>Mage::helper('designersoftware')->__('Parts information')));
     
      if($this->getRequest()->getParam('id')):		
		$disabled = true;
	  else:
		$disabled = false;
      endif;
      
      $stores = Mage::helper('designersoftware/store')->getAllStores();
      foreach($stores as $store):
		$storeId	= $store['store_id'];
		$storeLabel = $store['label'];
		  if($storeId==1):
			$disabled = true;
		  else:
			$disabled = false;
		  endif;	
		  $fieldset->addField('title['.$storeId.']', 'text', array(
			  'label'     => Mage::helper('designersoftware')->__('Title '.$storeLabel),
			  'class'     => 'required-entry',
			  'required'  => true,
			  'disabled'  => $disabled,	
			  'name'      => 'title['.$storeId.']',			  
		  ));
      endforeach;
      
       /*$fieldset->addField('title', 'text', array(
			  'label'     => Mage::helper('designersoftware')->__('Title'),
			  'class'     => 'required-entry',
			  'required'  => true,
			  'disabled'  => $disabled,	
			  'name'      => 'title',			  
		  ));
      
     $fieldset->addField('code', 'text', array(
          'label'     => Mage::helper('designersoftware')->__('Code'),
          'class'     => 'required-entry',
          'required'  => true,
          'disabled'  => $disabled,	
          'name'      => 'code',
      ));*/      
  
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
     
      if ( Mage::getSingleton('adminhtml/session')->getPartsData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getPartsData());
          Mage::getSingleton('adminhtml/session')->setPartsData(null);
      } elseif ( Mage::registry('parts_data') ) {		  
          $form->setValues($this->getData());
      }
      return parent::_prepareForm();
  }   

  public function getData(){
	  $regData = Mage::registry('parts_data')->getData();		  
	  $storeData = Mage::helper('designersoftware/store_parts')->getDataById($regData['parts_id']);
	  
	  foreach($storeData as $store):
		$regData['title['.$store['store_id'].']'] = $store['title'];
	  endforeach;
	  
	  return $regData;
  }
}
