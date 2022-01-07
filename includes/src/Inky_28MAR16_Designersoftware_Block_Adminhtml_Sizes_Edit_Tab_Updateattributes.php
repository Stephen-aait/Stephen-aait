<?php
class Inky_Designersoftware_Block_Adminhtml_Sizes_Edit_Tab_Updateattributes extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('sizes_form', array('legend'=>Mage::helper('designersoftware')->__('Sizes information')));
      
      $fromData = Mage::registry('sizes_data');
     
      $manageSizesIds=$this->getRequest()->getParams();
       //echo '<pre>';print_r($manageSizesIds);exit;	
      $manageSizesIds = serialize($manageSizesIds['sizes']);
      $fromData->setUpdateSizesIds($manageSizesIds);
	  //echo '<pre>';print_r($fromData);exit;	  
        
      $fieldset->addField('update_sizes_ids', 'textarea', array(			
			'style'     => 'height:75px;display:none;',
			'name'      => 'update_sizes_ids',								
		));
	     
      // Colorable
      $colorable=$fieldset->addField('colorable', 'select', array(
          'label'     => Mage::helper('designersoftware')->__('Colorable'),
          'name'      => 'colorable',
          'values'    => array(
              array(
                  'value'     => 0,
                  'label'     => Mage::helper('designersoftware')->__('No'),
              ),

              array(
                  'value'     => 1,
                  'label'     => Mage::helper('designersoftware')->__('Yes'),
              ),
          ),          
      ));
      $checkboxAfterItem = $this->checkboxAfterItem($colorable->getId());
      $colorable->setAfterElementHtml($checkboxAfterItem);      
	  
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
            
      if ( Mage::getSingleton('adminhtml/session')->getSizesData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getSizesData());
          Mage::getSingleton('adminhtml/session')->setSizesData(null);
      } elseif ( Mage::registry('sizes_data') ) {
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
