<?php 
class Emipro_Customoptions_Block_Adminhtml_Removecustomoptions_Edit_Tab_Removesku extends Mage_Adminhtml_Block_Widget_Form {
	public function _prepareForm() {

        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('_skulist');
        $form->setFieldNameSuffix('skulist');
        $this->setForm($form);
        $fieldset = $form->addFieldset('removesku_form', array('legend' => Mage::helper('emipro_customoptions')->__('Sku List')));

        $fieldset->addField('sku_list', 'textarea', array(
            'label' => Mage::helper('emipro_customoptions')->__('Sku List'),
            'name' => 'sku_list',
            'note'=>'Enter sku list comma(,) seprated.eg(sku1,sku2)',
            'required'=>true,
        ));
        return parent::_prepareForm();
    }

}
