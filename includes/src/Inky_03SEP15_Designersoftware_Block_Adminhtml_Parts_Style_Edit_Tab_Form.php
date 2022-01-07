<?php

class Inky_Designersoftware_Block_Adminhtml_Parts_Style_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('parts_style_form', array('legend'=>Mage::helper('designersoftware')->__('Parts Style Info')));
     
      if($this->getRequest()->getParam('id')):
		$disabled = true;
	  else:
		$disabled = false;
      endif;
      
      $stores = Mage::helper('designersoftware/store')->getAllStores();
      foreach($stores as $store):
		$storeId	= $store['store_id'];
		$storeLabel = $store['label'];		  	
		  $fieldset->addField('title['.$storeId.']', 'text', array(
			  'label'     => Mage::helper('designersoftware')->__('Title '.$storeLabel),
			  'class'     => 'required-entry',
			  'required'  => true,			  
			  'name'      => 'title['.$storeId.']',			  
		  ));
      endforeach;
      
      /*$fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('designersoftware')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));
      */
      
      $parts = Mage::helper('designersoftware')->getParts();
      $fieldset->addField('parts_id', 'select', array(
          'label'     => Mage::helper('designersoftware')->__('Part'),
          'class'     => 'required-entry',
          'required'  => true,
          'disabled'  => $disabled,
          'name'      => 'parts_id',
          'values' 	  => $parts,          
      ));
      
      $fieldset->addField('code', 'text', array(
          'label'     => Mage::helper('designersoftware')->__('Code'),
          'class'     => 'required-entry',
          'required'  => true,
          'disabled'  => $disabled,	
          'name'      => 'code',
      )); 
      
      $fieldset->addField('price', 'text', array(
          'label'     => Mage::helper('designersoftware')->__('Price'),          
          'name'      => 'price',
      ));     
	  	   
      for($i=0;$i<100;$i++){
		  $array[$i]['value']=$i+1;
		  $array[$i]['label']=$i+1;
	  }							
	  $sortValues =	$array;
	  $fieldset->addField('sort_order', 'select', array(
          'label'     => Mage::helper('designersoftware')->__('Priority'),
          'name'      => 'sort_order',
          'values'    => $sortValues,
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
     
      if ( Mage::getSingleton('adminhtml/session')->getPartsStyleData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getPartsStyleData());
          Mage::getSingleton('adminhtml/session')->setPartsStyleData(null);
      } elseif ( Mage::registry('parts_style_data') ) {
          //$form->setValues(Mage::registry('parts_style_data')->getData());
           $form->setValues($this->getData());
      }
      return parent::_prepareForm();
  }
  
  public function getData(){
	  $regData = Mage::registry('parts_style_data')->getData();		  
	  $storeData = Mage::helper('designersoftware/store_parts_style')->getDataById($regData['parts_style_id']);
	  
	  foreach($storeData as $store):
		$regData['title['.$store['store_id'].']'] = $store['title'];
	  endforeach;
	  
	  return $regData;
  }
}
