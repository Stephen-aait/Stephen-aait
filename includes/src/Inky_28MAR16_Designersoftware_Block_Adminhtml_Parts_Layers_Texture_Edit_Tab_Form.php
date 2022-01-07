<?php

class Inky_Designersoftware_Block_Adminhtml_Parts_Layers_Texture_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('texture_form', array('legend'=>Mage::helper('designersoftware')->__('Texture information')));
     
      $fromData = Mage::registry('parts_layers_texture_data')->getData();
      
      if($this->getRequest()->getParam('id')):
		$disabled = true;
	  else:
		$disabled = false;
      endif;
      
      
      /*$fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('designersoftware')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));
	  */
	  
      $parts = $fieldset->addField('parts_id', 'select', array(
          'label'     => Mage::helper('designersoftware')->__('Part'),
          'class'     => 'required-entry',
          'required'  => true,
          'disabled'  => $disabled,
          'name'      => 'parts_id',
          'values' 	  => Mage::helper('designersoftware')->getParts(true),
          'onchange'  => 'getPartsLayers(this)',          
      ));
      
      $parts->setAfterElementHtml("<script type=\"text/javascript\">
                    function getPartsLayers(selectElement){
                        var reloadurl = '" . $this->getUrl('*/*/getPartsLayers') . "parts_id/' + selectElement.value;
                                                  
                        new Ajax.Request(reloadurl, {
                            method: 'get',
                            onLoading: function (partslayersform) {
                                $('parts_layers_id').update('Searching...');
                            },
                            onComplete: function(partslayersform) { //alert(partslayersform.responseText); 
                                $('parts_layers_id').update(partslayersform.responseText);
                            }
                        });
                        
                        var reloadurl1 = '" . $this->getUrl('*/*/getTexture') . "parts_id/' + selectElement.value;
                         new Ajax.Request(reloadurl1, {
                            method: 'get',
                            onLoading: function (textureform) {
                                $('texture_id').update('Searching...');
                            },
                            onComplete: function(textureform) { //alert(textureform.responseText); 
                                $('texture_id').update(textureform.responseText);
                            }
                        });
                        
                        
                    }
                </script>");
        
        $partsLayers = $fieldset->addField('parts_layers_id', 'select', array(
			  'label'     => Mage::helper('designersoftware')->__('Parts Layers'),
			  'class'     => 'required-entry',
			  'required'  => true,
			  'disabled'  => $disabled,
			  'name'      => 'parts_layers_id',
			  'values' 	  => Mage::helper('designersoftware')->getPartsLayers($fromData['parts_id']),
			  'onchange'  => 'getPartsLayersTexture(this)',
		  ));
		
		$partsLayers->setAfterElementHtml("<script type=\"text/javascript\">
                    function getPartsLayersTexture(selectElement){
                         var reloadurl1 = '" . $this->getUrl('*/*/getTexture') . "parts_layers_id/' + selectElement.value;
                         new Ajax.Request(reloadurl1, {
                            method: 'get',
                            onLoading: function (textureform) {
                                $('texture_id').update('Searching...');
                            },
                            onComplete: function(textureform) { //alert(textureform.responseText); 								
                                $('texture_id').update(textureform.responseText);
                            }
                        });                        
                    }
                </script>");
  	
		
		$partsLayersTexture = $fieldset->addField('texture_id', 'select', array(
				  'label'     => Mage::helper('designersoftware')->__('Texture'),
				  'class'     => 'required-entry',
				  'required'  => true,
				  'disabled'  => $disabled,
				  'name'      => 'texture_id',
				  'values' 	  => Mage::helper('designersoftware')->getPartsLayersTexture($fromData['parts_layers_id']),
			  ));         
        
      
      /*foreach($parts as $key=>$part):
		  $partsLayersArr = Mage::helper('designersoftware')->getPartsLayers($key); 		  
		  if(count($partsLayersArr)>0): 
			  $fieldset->addField('parts_layers_id['. $key .']', 'select', array(
				  'label'     => Mage::helper('designersoftware')->__('Parts Layers'),
				  'class'     => 'required-entry',
				  'required'  => true,
				  //'disabled'  => $disabled,
				  'name'      => 'parts_layers_id['. $key .']',
				  'values' 	  => $partsLayersArr,
				  'onchange'  => 'selectTexture(this)',
			  ));   		    
		  endif;
	  endforeach;    
	  
	   foreach($partsLayersArr as $key=>$partsLayers):
		  $partsTexture = Mage::helper('designersoftware')->getPartsLayersTexture($key); 		  
		  echo '<pre>';print_r($partsTexture);exit;
		  if(count($partsTexture)>0): 
			  $fieldset->addField('texture_id['. $key .']', 'select', array(
				  'label'     => Mage::helper('designersoftware')->__('Texture'),
				  'class'     => 'required-entry',
				  'required'  => true,
				  //'disabled'  => $disabled,
				  'name'      => 'texture_id['. $key .']',
				  'values' 	  => $partsTexture,
			  ));   		    
		  endif;
	  endforeach;         
      */    
	  
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
     
      if ( Mage::getSingleton('adminhtml/session')->getPartsLayersTextureData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getPartsLayersTextureData());
          Mage::getSingleton('adminhtml/session')->setPartsLayersTextureData(null);
      } elseif ( Mage::registry('parts_layers_texture_data') ) {
          $form->setValues(Mage::registry('parts_layers_texture_data')->getData());
      }
      return parent::_prepareForm();
  }
}
