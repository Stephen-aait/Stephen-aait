<?php

class Inky_Designersoftware_Block_Adminhtml_Font_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('font_form', array('legend'=>Mage::helper('designersoftware')->__('Font information')));
     
      $fieldset->addField('font_ai', 'text', array(
          'label' => Mage::helper('designersoftware')->__('Font AI'),
          'class' => 'required-entry',
          'required' => true,
          'name' => 'font_ai',
      ));
      
      $fieldset->addField('title', 'text', array(
          'label' => Mage::helper('designersoftware')->__('Title'),
          'class' => 'required-entry',
          'required' => true,
          'name' => 'title',
      ));
     
     // Check that the content is for New or in Edit Mode
     
     if ($this->getRequest()->getParam('id')) {
         $font = Mage::getModel('designersoftware/font')->getCollection()
                 ->addFieldToFilter('font_id',$this->getRequest()->getParam('id'))
                 ->getFirstItem()
                 ->getData();
         
         //echo '<pre>';
        // print_r($font);
         $valueTtf = $font['filename_ttf'];
         $valueBoldTtf = $font['filename_boldttf'];
         $valueItalicTtf = $font['filename_italicttf'];
         $valueBoldItalicTtf = $font['filename_bolditalicttf'];
         
         
        //Check if the required ttf file is entered
         if($valueTtf!=''){ $requiredTtf = false; } else { $requiredTtf = true; }
         if($valueBoldTtf!=''){ $requiredBoldTtf = false; } else { $requiredBoldTtf= true; }
         if($valueItalicTtf!=''){ $requiredItalicTtf = false; } else { $requiredItalicTtf = true; }
         if($valueBoldItalicTtf!=''){ $requiredBoldItalicTtf = false; } else { $requiredBoldItalicTtf = true; }
         
         
         // Image section for TTF files
         $siteUrl = Mage::getBaseUrl('media');
         $imageUrl = '<img src="' . $siteUrl . 'inky/font/image/' . $font['ttf_image_name'] . '" title="Font Image" alt="Font Image"  />';
         $imageBoldUrl = '<img src="' . $siteUrl . 'inky/font/image/bold/' . $font['boldttf_image_name'] . '" title="Font Bold Image" alt="Font Bold Image"  />';
         $imageItalicUrl = '<img src="' . $siteUrl . 'inky/font/image/italic/' . $font['italicttf_image_name'] . '" title="Font Italic Image" alt="Font Italic Image"  />';
         $imageBoldItalicUrl = '<img src="' . $siteUrl . 'inky/font/image/bolditalic/' . $font['bolditalicttf_image_name'] . '" title="Font Bold-Italic Image" alt="Font Bold-Italic Image"  />';
      
         
     } else {
         
         $valueTtf = '';
         $valueBoldTtf = '';
         $valueItalicTtf = '';
         $valueBoldItalicTtf = '';
         
         $requiredTtf = true;
         $requiredBoldTtf = true;
         $requiredItalicTtf = true;
         $requiredBoldItalicTtf = true;
         
         // Image section for TTF files
         $imageUrl = "";
         $imageBoldUrl = "";
         $imageItalicUrl = "";
         $imageBoldItalicUrl = "";
         
     }   
     
     // ---------------------------------
    
     $fieldset->addField('filename_ttf', 'file', array(
          'label'     => Mage::helper('designersoftware')->__('Font TTF File'),
          'required'  => $requiredTtf,
          'width'     => '150px',
          'height'    => '100px',
          'filter'    => false,
          'sortable'  => false,
          'name'      => 'filename_ttf',
          'after_element_html' => Mage::helper('designersoftware')->__('Upload only TTF file format.'). '<p style="padding-top:20px;">' . $imageUrl . '</p>',
         'onchange'   => "valImgExt(this,'ttf')",
         'value'      => $valueTtf
	  ));
            
     
     $fieldset->addField('filename_boldttf', 'file', array(
          'label'     => Mage::helper('designersoftware')->__('Font Bold TTF File'),
          'required'  => $requiredBoldTtf,
          'width'     => '150px',
          'height'    => '100px',
          'filter'    => false,
          'sortable'  => false,
          'name'      => 'filename_boldttf',
          'after_element_html' => Mage::helper('designersoftware')->__('Upload only TTF file format.'). '<p style="padding-top:20px;">' . $imageBoldUrl . '</p>',
          'onchange'   => "valImgExt(this,'ttf')",
          'value'      => $valueBoldTtf
	  ));
        
     $fieldset->addField('filename_italicttf', 'file', array(
          'label'     => Mage::helper('designersoftware')->__('Font Italic TTF File'),
          'required'  => $requiredItalicTtf,
          'width'     => '150px',
          'height'    => '100px',
          'filter'    => false,
          'sortable'  => false,
          'name'      => 'filename_italicttf',
          'after_element_html' => Mage::helper('designersoftware')->__('Upload only TTF file format.'). '<p style="padding-top:20px;">' . $imageItalicUrl . '</p>',
          'onchange'   => "valImgExt(this,'ttf')",
          'value'      => $valueItalicTtf
	  ));
     
     
     $fieldset->addField('filename_bolditalicttf', 'file', array(
          'label'     => Mage::helper('designersoftware')->__('Font Bold Italic TTF File'),
          'required'  => $requiredBoldItalicTtf,
          'width'     => '150px',
          'height'    => '100px',
          'filter'    => false,
          'sortable'  => false,
          'name'      => 'filename_bolditalicttf',
          'after_element_html' => Mage::helper('designersoftware')->__('Upload only TTF file format.'). '<p style="padding-top:20px;">' . $imageBoldItalicUrl . '</p>',
          'onchange'   => "valImgExt(this,'ttf')",
          'value'      => $valueBoldItalicTtf
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
     
      if ( Mage::getSingleton('adminhtml/session')->getFontData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getFontData());
          Mage::getSingleton('adminhtml/session')->setFontData(null);
      } elseif ( Mage::registry('font_data') ) {
          $form->setValues(Mage::registry('font_data')->getData());
      }
      return parent::_prepareForm();
  }
}
