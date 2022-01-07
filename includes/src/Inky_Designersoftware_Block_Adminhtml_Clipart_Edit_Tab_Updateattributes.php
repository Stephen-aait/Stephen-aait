<?php
class Inky_Designersoftware_Block_Adminhtml_Clipart_Edit_Tab_Updateattributes extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('clipart_form', array('legend'=>Mage::helper('designersoftware')->__('Clipart information')));
      
      $fromData = Mage::registry('clipart_data');
     
      $manageClipartIds=$this->getRequest()->getParams();
       //echo '<pre>';print_r($manageClipartIds);exit;	
      $manageClipartIds = serialize($manageClipartIds['clipart']);
      $fromData->setUpdateClipartIds($manageClipartIds);
	  //echo '<pre>';print_r($fromData);exit;	  
        
      $fieldset->addField('update_clipart_ids', 'textarea', array(			
			'style'     => 'height:75px;display:none;',
			'name'      => 'update_clipart_ids',								
		));
		 
	  	
      // Category
      $clipartCategory = Mage::helper('designersoftware')->getClipartCategory();
      $category=$fieldset->addField('clipart_category_id', 'select', array(
            'label' => Mage::helper('designersoftware')->__('Category'),
            'name' => 'clipart_category_id',
            'class' => 'required-entry',
            'required' => true,
            'values' => $clipartCategory, // Here 1 is an ID of Menu Group Not defined in Database     
        ));                 
      $checkboxAfterItem = $this->checkboxAfterItem($category->getId());
      $category->setAfterElementHtml($checkboxAfterItem);
	       
      
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
      
      
      // Print
      $colorable=$fieldset->addField('print', 'select', array(
          'label'     => Mage::helper('designersoftware')->__('Part'),
          'name'      => 'print',
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
            
      if ( Mage::getSingleton('adminhtml/session')->getClipartData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getClipartData());
          Mage::getSingleton('adminhtml/session')->setClipartData(null);
      } elseif ( Mage::registry('clipart_data') ) {
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
