<?php

class Inky_Designersoftware_Block_Adminhtml_Sizes_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('sizes_form', array('legend'=>Mage::helper('designersoftware')->__('Sizes information')));
      
      $fromData = Mage::registry('sizes_data')->getData();
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('designersoftware')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));
      
      $fieldset->addField('price', 'text', array(
          'label'     => Mage::helper('designersoftware')->__('Sizes Price'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'price',
      ));

     
     $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('designersoftware')->__('File'),
          'required'  => false,
          'name'      => 'filename',          
          'after_element_html' => $fromData['filename']!=''?'<p><img src="' . Mage::helper('designersoftware/image')->_thumbWebPath(100,100) . $fromData['filename']. '"/></p>':'',
	  ));
	  
	  $fieldset->addField('colorable', 'checkbox', array(
            'label' => Mage::helper('designersoftware')->__('Colorable'),
            'checked' => $fromData['colorable']==1?true:false,
            'onclick' => 'this.value = this.checked ? 1:0',
            'name' => 'colorable',
        ));
	  
	  
	  $defaultSizesColors = Mage::helper('designersoftware')->getSizesColors($this->getRequest()->getParam('id'));      
      if($fromData['default_color_id']>0):
		//echo $fromData['default_color_id'];exit;
		$colorCollection = Mage::getModel('designersoftware/color')->getCollection()
								->addFieldToFilter('color_id',$fromData['default_color_id'])
								->getFirstItem();
								
		$colorCode = $colorCollection->getColorcode();
	  else:
		//echo 'else';exit;
		foreach($defaultSizesColors as $key=>$value):
			$colorCode = $key;
			break;
		endforeach;
      endif;
      
      if(count($defaultSizesColors)>0 && $defaultSizesColors!=false):
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
          'values' 	  => $defaultSizesColors,
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
     
      if ( Mage::getSingleton('adminhtml/session')->getSizesData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getSizesData());
          Mage::getSingleton('adminhtml/session')->setSizesData(null);
      } elseif ( Mage::registry('sizes_data') ) {          
          $formData = Mage::registry('sizes_data')->getData();
          
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
}
