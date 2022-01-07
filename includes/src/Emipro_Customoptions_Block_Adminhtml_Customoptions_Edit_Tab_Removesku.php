<?php 
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'On');

class Emipro_Customoptions_Block_Adminhtml_Customoptions_Edit_Tab_Removesku extends Mage_Adminhtml_Block_Widget_Form {

	public function _prepareForm() {

        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('_removesku');
        $form->setFieldNameSuffix('removesku');
        $this->setForm($form);
        $fieldset = $form->addFieldset('removesku_form', array('legend' => Mage::helper('customoptions')->__('Sku List')));
//       $prodAttr=Mage::getModel("googlefeed/googlefeed")->toOptionArray();	

        $fieldset->addField('remove_sku', 'textarea', array(
            'label' => Mage::helper('customoptions')->__('Sku List'),
            'name' => 'remove_sku',
            'note'=>'Enter sku list comma(,) seprated.eg(sku1,sku2)',
        ));
        return parent::_prepareForm();
    }

}
