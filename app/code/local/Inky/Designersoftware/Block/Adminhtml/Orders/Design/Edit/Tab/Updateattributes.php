<?php
class Inky_Designersoftware_Block_Adminhtml_Designersoftware_Edit_Tab_Updateattributes extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('designersoftware_form', array('legend'=>Mage::helper('designersoftware')->__('Update Designs info')));
      
      $fromData = Mage::registry('designersoftware_data');
	
	  //echo "<pre>";print_r($fromData);exit;	
      $manageDesignersoftwareIds=$this->getRequest()->getParams();     
      $manageDesignersoftwareIds = serialize($manageDesignersoftwareIds['designersoftware']);
      $fromData->setUpdateDesignersoftwareIds($manageDesignersoftwareIds);
	  //echo '<pre>';print_r($fromData);exit;	  
        
      $fieldset->addField('update_designersoftware_ids', 'textarea', array(			
			'style'     => 'height:75px;display:none;',
			'name'      => 'update_designersoftware_ids',								
	  ));
	  
	  //Default Color
	  /*$defaultLayerColors = Mage::helper('designersoftware')->getColor();
	  $defaultColor=$fieldset->addField('default_color_id', 'select', array(
          'label'     => Mage::helper('designersoftware')->__('Default Color'),
          'class'     => $class,
          'required'  => $required,          
          'name'      => 'default_color_id',
          'values' 	  => $defaultLayerColors,
          'onchange'  => 'selectColor(this)',
          //'after_element_html' => empty($colorCode)?'':'<p id="default_color_block" style="width:277px;height:28px;background-color:#'.$colorCode.';border:solid thin"></p>',  
      ));	  
	  $checkboxAfterItem = $this->checkboxAfterItem($defaultColor->getId());
      $defaultColor->setAfterElementHtml($checkboxAfterItem);*/
	  
	  
	  // Title
	  $title=$fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('designersoftware')->__('Title'),                    
          'name'      => 'title',          
      ));
	  $checkboxAfterItem = $this->checkboxAfterItem($title->getId());
      $title->setAfterElementHtml($checkboxAfterItem);
      
	  
	  // Gallery Status
      $galleryStatus=$fieldset->addField('gallery_status', 'select', array(
          'label'     => Mage::helper('designersoftware')->__('Gallery Status'),
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
      
      $checkboxAfterItem = $this->checkboxAfterItem($galleryStatus->getId());
      $galleryStatus->setAfterElementHtml($checkboxAfterItem);
	  
      // Status
      $status=$fieldset->addField('status', 'select', array(
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
      
      $checkboxAfterItem = $this->checkboxAfterItem($status->getId());
      $status->setAfterElementHtml($checkboxAfterItem);
            
      if ( Mage::getSingleton('adminhtml/session')->getDesignersoftwareData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getDesignersoftwareData());
          Mage::getSingleton('adminhtml/session')->setDesignersoftwareData(null);
      } elseif ( Mage::registry('designersoftware_data') ) {
          $form->setValues($fromData);
      }
      return parent::_prepareForm();
  }
  
  public function checkboxAfterItem($id){
	   $checkboxAfterItem = '<span class="attribute-change-checkbox">
								<input type="checkbox" onclick="toogleFieldEditMode(this, \''.$id.'\')" id="'.$id.'-checkbox">
								<label for="'.$id.'-checkbox">Change</label>
							</span>
							<script type="text/javascript">initDisableFields(\''.$id.'\')</script>';
	  
	  return $checkboxAfterItem;
  } 
  
}
