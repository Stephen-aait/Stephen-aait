<?php

class Inky_Designersoftware_Block_Adminhtml_Parts_Layers_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('parts_layers_form', array('legend'=>Mage::helper('designersoftware')->__('Layers information')));
     
      $fromData = Mage::registry('parts_layers_data')->getData();      
      //echo '<pre>';print_r($fromData);exit;
      
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
			  //'disabled'  => $disabled,	
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
      
      $parts = Mage::helper('designersoftware')->getPartsStyle();
      $fieldset->addField('parts_style_id', 'select', array(
          'label'     => Mage::helper('designersoftware')->__('Parts Style'),
          'class'     => 'required-entry',
          'required'  => true,
          'disabled'  => $disabled,
          'name'      => 'parts_style_id',
          'values' 	  => $parts,          
      ));     
      
      // ---------------- Default Color concept ------ starts -----
      
      $fieldset->addField('layer_code', 'text', array(
          'label'     => Mage::helper('designersoftware')->__('Layer Code'),
          'class'     => 'required-entry',
          'required'  => true,
          'disabled'  => $disabled,
          'name'      => 'layer_code',          
      ));
      
      $defaultLayerColors = Mage::helper('designersoftware')->getLayerColors($this->getRequest()->getParam('id'));      
      if($fromData['default_color_id']>0):
		//echo $fromData['default_color_id'];exit;
		$colorCollection = Mage::getModel('designersoftware/color')->getCollection()
								->addFieldToFilter('color_id',$fromData['default_color_id'])
								->getFirstItem();
								
		$colorCode = $colorCollection->getColorcode();
	  else:
		//echo 'else';exit;
		foreach($defaultLayerColors as $key=>$value):
			$colorCode = $key;
			break;
		endforeach;
      endif;
      
      if(count($defaultLayerColors)>0 && $defaultLayerColors!=false):
		$class = 'required-entry';
		$required = true;
	  else:
		$class = '';
		$required = false;
      endif;
      $fieldset->addField('default_color_id', 'select', array(
          'label'     => Mage::helper('designersoftware')->__('Default Color'),
          'class'     => $class,
          'required'  => $required,          
          'name'      => 'default_color_id',
          'values' 	  => $defaultLayerColors,
          'onchange'  => 'selectColor(this)',
          'after_element_html' => empty($colorCode)?'':'<p id="default_color_block" style="width:277px;height:28px;background-color:#'.$colorCode.';border:solid thin"></p>',  
      ));           
      
      // ---------------- Default Color concept starts       ---- ENDS -----
	      
      
      $fieldset->addField('default_color_price', 'text', array(
          'label'     => Mage::helper('designersoftware')->__('Default Color Price'),
          'class'     => $class,
          'required'  => $required,          
          'name'      => 'default_color_price', 
          'after_element_html' => '<p class="note"><span></span>Enter default price for all colors you want to set for this Layer</span></p>',
      ));
      
      
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('designersoftware')->__('Content'),
          'title'     => Mage::helper('designersoftware')->__('Content'),
          'style'     => 'width:274px; height:93px;',
          'wysiwyg'   => false,
          'required'  => false,
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
     
      if ( Mage::getSingleton('adminhtml/session')->getPartsLayersData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getPartsLayersData());
          Mage::getSingleton('adminhtml/session')->setPartsLayersData(null);
      } elseif ( Mage::registry('parts_layers_data') ) {          
          //$formData = Mage::registry('parts_layers_data')->getData();
          
          $formData = $this->getData();
          // Update coming Data array accordingly          
          $formData = $this->updateFormData($formData);
          
          $form->setValues($formData);
      }
      return parent::_prepareForm();
  }
  
   public function updateFormData($formData){
     
      //$style_design_ids = unserialize($formData['style_design_ids']);
      //$partsTypeId = $formData['parts_type_id'];
      //$partsId = $formData['parts_id'];      
      $colorCollection = Mage::getModel('designersoftware/color')->getCollection()
							->addFieldToFilter('color_id',$formData['default_color_id'])
							->getFirstItem();								
      
      $formData['default_color_id'] = $colorCollection->getColorcode();	
      //echo '<pre>';print_r($formData);exit;
	  	
      return $formData;      
  }
  
  public function getData(){
	  $regData = Mage::registry('parts_layers_data')->getData();
	  if(isset($regData['parts_layers_id']) && $regData['parts_layers_id']>0):			  
	  
		$storeData = Mage::helper('designersoftware/store_parts_layers')->getDataById($regData['parts_layers_id']);	  
		foreach($storeData as $store):
			$regData['title['.$store['store_id'].']'] = $store['title'];
		endforeach;
		
	  endif;
	  
	  return $regData;
  }
  
}
