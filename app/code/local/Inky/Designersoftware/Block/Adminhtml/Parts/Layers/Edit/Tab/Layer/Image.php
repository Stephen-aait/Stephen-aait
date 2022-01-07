<?php

class Inky_Designersoftware_Block_Adminhtml_Parts_Layers_Edit_Tab_Layer_Image extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      
     
      $fieldset = $form->addFieldset('parts_layers_form', array('legend'=>Mage::helper('designersoftware')->__('Image information'))); 
         
          
      $fromData = Mage::registry('parts_layers_data')->getData();      
      //echo '<pre>';print_r($fromData);exit;
      
      if($this->getRequest()->getParam('id')):
		$disabled = true;
	  else:
		$disabled = false;
      endif;
       
       
        $fieldset->addField('label', 'label', array(
          'value'     => Mage::helper('designersoftware')->__('Label Text'),
        ));
      
      //echo '<pre>';print_r($parts);exit;
      /* foreach($parts as $key=>$part):
		  $partsTypeArr = Mage::helper('designersoftware')->getPartsType($key); 
		  if(count($partsTypeArr)>0): 
			  $fieldset->addField('parts_type_id['. $key .']', 'select', array(
				  'label'     => Mage::helper('designersoftware')->__('Parts Type'),
				  'class'     => 'required-entry',
				  'required'  => true,
				  'disabled'  => $disabled,
				  'name'      => 'parts_type_id['. $key .']',
				  'values' 	  => $partsTypeArr,
			  ));   		    
		  endif;
	  endforeach;
      
      
     $fieldset->addField('leather_id', 'select', array(
          'label'     => Mage::helper('designersoftware')->__('Leather'),
          'class'     => 'required-entry',
          'required'  => true,
          'disabled'  => $disabled,
          'name'      => 'leather_id',
          'values' => Mage::helper('designersoftware')->getLeather(),          
      ));    
	  */

      $anglesCollection = Mage::getModel('designersoftware/angles')->getCollection()->addFieldToFilter('status',1);	
      $partsStyleCollection  = Mage::getModel('designersoftware/parts_style')->getCollection()->addFieldToFilter('parts_style_id', $fromData['parts_style_id'])->addFieldToFilter('status',1)->getFirstItem()->getData();	
      
      $midPath = $partsStyleCollection['code'] . DS  . $fromData['layer_code'] . DS . '20x90';
      foreach($anglesCollection as $angle):
		  
		  $fileDirPath = Mage::helper('designersoftware/parts_layers')->getColorableDirPath($midPath, $angle->getTitle());
		  $fileWebPath = Mage::helper('designersoftware/parts_layers')->getColorableWebPath($midPath, $angle->getTitle());
		  
		  $fieldset->addField($angle->getTitle(), 'file', array(
			  'label'     => Mage::helper('designersoftware')->__('Layer '.$angle->getTitle().' Angle'),
			  'required'  => false,
			  'name'      => 'filename['.$angle->getTitle().']',
			  'after_element_html' => file_exists($fileDirPath)?'<p><img src="' . $fileWebPath . '" width="20"/></p><br>Remove Image: <input type="checkbox" name="delImage['. $angle->getTitle() .']" id="delImage['. $angle->getTitle() .']" />':'',
		  ));
		  
	  endforeach;      
     
      if ( Mage::getSingleton('adminhtml/session')->getPartsLayersData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getPartsLayersData());
          Mage::getSingleton('adminhtml/session')->setPartsLayersData(null);
      } elseif ( Mage::registry('parts_layers_data') ) {          
          $formData = Mage::registry('parts_layers_data')->getData();
          
          // Update coming Data array accordingly          
          //$formData = $this->updateFormData($formData);
          
          $form->setValues($formData);
      }
      return parent::_prepareForm();
  }
  
   public function updateFormData($formData){
     
      $style_design_ids = unserialize($formData['style_design_ids']);
      $partsTypeId = $formData['parts_type_id'];
      $partsId = $formData['parts_id'];
      
      $formData['parts_type_id']=array($partsId=>$partsTypeId);
      $formData['style_design_ids'] = $style_design_ids;
      
      //echo '<pre>';print_r($formData);exit;
	  	
      return $formData;      
  }
}
