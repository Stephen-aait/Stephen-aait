<?php
class Inky_Designersoftware_Block_Adminhtml_Parts_Layers_Edit_Tab_Updateattributes extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('parts_layers_form', array('legend'=>Mage::helper('designersoftware')->__('Parts Layers info')));
      
      $fromData = Mage::registry('parts_layers_data');
     
      $managePartsLayersIds=$this->getRequest()->getParams();     
      $managePartsLayersIds = serialize($managePartsLayersIds['parts_layers']);
      $fromData->setUpdatePartsLayersIds($managePartsLayersIds);
	  //echo '<pre>';print_r($fromData);exit;	  
        
      $fieldset->addField('update_parts_layers_ids', 'textarea', array(			
			'style'     => 'height:75px;display:none;',
			'name'      => 'update_parts_layers_ids',								
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
            
      if ( Mage::getSingleton('adminhtml/session')->getPartsLayersData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getPartsLayersData());
          Mage::getSingleton('adminhtml/session')->setPartsLayersData(null);
      } elseif ( Mage::registry('parts_layers_data') ) {
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
