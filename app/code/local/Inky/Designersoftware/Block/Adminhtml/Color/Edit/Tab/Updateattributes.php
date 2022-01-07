<?php
class Inky_Designersoftware_Block_Adminhtml_Color_Edit_Tab_Updateattributes extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('color_form', array('legend'=>Mage::helper('designersoftware')->__('Color information')));
      
      $fromData = Mage::registry('color_data');
     
      $manageColorIds=$this->getRequest()->getParams();     
      $manageColorIds = serialize($manageColorIds['color']);
      $fromData->setUpdateColorIds($manageColorIds);
	  //echo '<pre>';print_r($fromData);exit;	  
        
      $fieldset->addField('update_color_ids', 'textarea', array(			
			'style'     => 'height:75px;display:none;',
			'name'      => 'update_color_ids',								
		));
		 
	  // Clip Status
      $clipstatus=$fieldset->addField('clip_status', 'select', array(
          'label'     => Mage::helper('designersoftware')->__('Clipart Status'),
          'name'      => 'clip_status',
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
      $checkboxAfterItem = $this->checkboxAfterItem($clipstatus->getId());
      $clipstatus->setAfterElementHtml($checkboxAfterItem);	
           
	  
	  // Text Status
      $textstatus=$fieldset->addField('text_status', 'select', array(
          'label'     => Mage::helper('designersoftware')->__('Text Status'),
          'name'      => 'text_status',
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
      $checkboxAfterItem = $this->checkboxAfterItem($textstatus->getId());
      $textstatus->setAfterElementHtml($checkboxAfterItem);
	  
	  
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
            
      if ( Mage::getSingleton('adminhtml/session')->getColorData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getColorData());
          Mage::getSingleton('adminhtml/session')->setColorData(null);
      } elseif ( Mage::registry('color_data') ) {
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
