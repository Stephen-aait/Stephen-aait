<?php 
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'On');

class Emipro_Customoptions_Block_Adminhtml_Customoptions_Edit_Tab_Sku extends Mage_Adminhtml_Block_Widget_Form {

	public function _prepareForm() {

        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('_productsku');
        $form->setFieldNameSuffix('productsku');
        $this->setForm($form);
        $fieldset = $form->addFieldset('productsku_form', array('legend' => Mage::helper('customoptions')->__('Sku List')));
//       $prodAttr=Mage::getModel("googlefeed/googlefeed")->toOptionArray();	

        $fieldset->addField('sku_list', 'textarea', array(
            'label' => Mage::helper('customoptions')->__('Sku List'),
            'name' => 'sku_list',
            'note'=>'Enter sku list comma(,) seprated.eg(sku1,sku2)',
        ));
        return parent::_prepareForm();
    }

}
