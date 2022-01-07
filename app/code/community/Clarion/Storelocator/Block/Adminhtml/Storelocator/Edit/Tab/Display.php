<?php
/**
 * Display tab class file
 * 
 * @category    Clarion
 * @package     Clarion_Storelocator
 * @author      Clarion Magento Team
 */

class Clarion_Storelocator_Block_Adminhtml_Storelocator_Edit_Tab_Display extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $model = Mage::registry('storelocator_data');
        $yesno = Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray();
        $enabledisable = Mage::getModel('adminhtml/system_config_source_enabledisable')->toOptionArray();
        
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('form_Genera_Display', array('legend'=>Mage::helper('clarion_storelocator')->__('Display')));
        
        $fieldset->addField('status', 'select', array(
            'name' => 'status',
            'label' => Mage::helper('clarion_storelocator')->__('Status'),
            'title' => Mage::helper('clarion_storelocator')->__('Status'),
            'value' => '1',
            'values' => $enabledisable,
        ));
        
       $data = $model->getData();
        if(!empty($data)) {
            $form->setValues($data);
        }
       return parent::_prepareForm();
    }
}