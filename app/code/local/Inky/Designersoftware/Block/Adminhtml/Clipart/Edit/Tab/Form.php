<?php

class Inky_Designersoftware_Block_Adminhtml_Clipart_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('clipart_form', array('legend'=>Mage::helper('designersoftware')->__('Clipart information')));
   ?>
      <script language="javascript" type="text/javascript">                                            
            myJquery("input[type=file]").live('change',function(){
                var currentId= myJquery(this).attr('id');
                var val = myJquery(this).val();
                                        
                switch(val.substring(val.lastIndexOf('.')+1).toLowerCase()){
                        case "eps":
                        return true;
                        break;
                        case "png":
                        return true;
                        break;                        
                        case "jpg":
                        return true;
                        break;                        
                        case "jpeg":
                        return true;
                        break;                        
                    default:
                        jQuery(this).val('');
                        alert("Only JPG, JPEG, PNG & EPS files are allowed!!!");
                        return false;
                        break;
                }
            });
            
          myJquery("#price").live('change',function(){
				var val = myJquery(this).val();
				if(myJquery.isNumeric(val)){
					return true;
				} else {
					jQuery(this).val('');
					alert("Price should be in correct format");
					return false;
				}
			  });                                               
        </script> 
   <?php
      $fromData = Mage::registry('clipart_data')->getData();
       
      /*$fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('designersoftware')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));
	  */
	  
	  
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
			  //'disabled'  => $disabled,	
			  'name'      => 'title['.$storeId.']',			  
		  ));
      endforeach;
	  
	  
	  $clipartCategory = Mage::helper('designersoftware')->getClipartCategory();
      $fieldset->addField('clipart_category_id', 'select', array(
          'label'     => Mage::helper('designersoftware')->__('Category'),
          'class'     => 'required-entry',
          'required'  => true,
          //'disabled'  => $disabled,
          'name'      => 'clipart_category_id',
          'values' 	  => $clipartCategory,          
      ));	
      
      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('designersoftware')->__('File'),
          'required'  => false,
          'name'      => 'filename',          
          'after_element_html' => $fromData['filename']!=''?'<p><img src="' . Mage::helper('designersoftware/image')->_thumbWebPath(224,224) . $fromData['filename']. '"/></p>':'',
	  ));       
	  
	   $fieldset->addField('colorable', 'checkbox', array(
            'label' => Mage::helper('designersoftware')->__('Colorable'),
            'checked' => $fromData['colorable']==1?true:false,
            'onclick' => 'this.value = this.checked ? 1:0',
            'name' => 'colorable',
        ));
        
       $fieldset->addField('print', 'checkbox', array(
            'label' => Mage::helper('designersoftware')->__('Part'),
            'checked' => $fromData['print']==1?true:false,
            'onclick' => 'this.value = this.checked ? 1:0',
            'name' => 'print',
       )); 
        
       $fieldset->addField('price', 'text', array(
          'label'     => Mage::helper('designersoftware')->__('Price'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'price',
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
     
      if ( Mage::getSingleton('adminhtml/session')->getClipartData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getClipartData());
          Mage::getSingleton('adminhtml/session')->setClipartData(null);
      } elseif ( Mage::registry('clipart_data') ) {          
		 //$form->setValues(Mage::registry('clipart_data')->getData());			        
		$form->setValues($this->getData());			
		
      }
      return parent::_prepareForm();
  }
  
  public function getData(){
	  $regData = Mage::registry('clipart_data')->getData();	
	  
	  if(isset($regData['clipart_id']) && $regData['clipart_id']>0):		  
		$storeData = Mage::helper('designersoftware/store_clipart')->getDataById($regData['clipart_id']);	  
		foreach($storeData as $store):
			$regData['title['.$store['store_id'].']'] = $store['title'];
		endforeach;	  
	  endif;
	  
	  return $regData;	 
  }
}
