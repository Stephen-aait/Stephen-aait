<?php
/**
 * General tab class file
 * 
 * @category    Clarion
 * @package     Clarion_Storelocator
 * @author      Clarion Magento Team
 */

class Clarion_Storelocator_Block_Adminhtml_Storelocator_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $model = Mage::registry('storelocator_data');
        $data = $model->getData();
        if ($data) {
            if(isset($data['store_logo'])) {
                $data['store_logo'] = 'clarion_storelocator/' . $data['store_logo'];
            }
        } else {
            $data = array();
        }
        
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('form_General', array('legend'=>Mage::helper('clarion_storelocator')->__('General information')));
        
        if ($model->getId()) {
            $fieldset->addField('storelocator_id', 'hidden', array(
                'name' => 'storelocator_id',
            ));
        }
        
                 /**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $field = $fieldset->addField('store_id', 'multiselect', array(
                'name'      => 'stores[]',
                'label'     => Mage::helper('clarion_storelocator')->__('Store View'),
                'title'     => Mage::helper('clarion_storelocator')->__('Store View'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
            
            $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
            $field->setRenderer($renderer);
        }
        else {
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId(),
            ));
        }
        
        $fieldset->addField('name', 'text', array(
          'label'     => Mage::helper('clarion_storelocator')->__('Store Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'name',
        ));
        
        $fieldset->addField('street_address', 'text', array(
          'label'     => Mage::helper('clarion_storelocator')->__('Street Address'),
          'name'      => 'street_address',
        ));
        
        $fieldset->addField('country', 'select', array(
            'name'  => 'country',
            'class'     => 'required-select',
            'required'  => true,
            'label'     => 'Country',
            'values'    => Mage::getModel('adminhtml/system_config_source_country')->toOptionArray(),
        ));
        
        $fieldset->addField('state', 'text', array(
          'label'     => Mage::helper('clarion_storelocator')->__('State'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'state',
        ));
        
        $fieldset->addField('city', 'text', array(
          'label'     => Mage::helper('clarion_storelocator')->__('City'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'city',
        ));
        
        $fieldset->addField('zipcode', 'text', array(
          'label'     => Mage::helper('clarion_storelocator')->__('Zipcode'),
          'required'  => true,
          'name'      => 'zipcode',
        ));
        
        $fieldset->addField('phone', 'text', array(
          'label'     => Mage::helper('clarion_storelocator')->__('Phone'),
          'name'      => 'phone',
        ));
        
        $fieldset->addField('fax', 'text', array(
          'label'     => Mage::helper('clarion_storelocator')->__('Fax'),
          'name'      => 'fax',
        ));        
        
        $fieldset->addField('url', 'text', array(
          'label'     => Mage::helper('clarion_storelocator')->__('Store Url'),
          'class'     => 'validate-clean-url',
          'name'      => 'url',
        ));
        
        $fieldset->addField('email', 'text', array(
          'label'     => Mage::helper('clarion_storelocator')->__('Email'),
          'class'     => 'validate-email',
          'name'      => 'email',
        ));
        
        $fieldset->addField('store_logo', 'image', array(
          'label'     => Mage::helper('clarion_storelocator')->__('Store Logo'),
          'name'      => 'store_logo',
          'note'      => 'Allowed extensions are jpg, jpeg, gif, png',
        ));
                      
        $fieldset->addField('trading_hours', 'text', array(
          'label'     => Mage::helper('clarion_storelocator')->__('Trading Hours'),
          'name'      => 'trading_hours',
        ));
        
        if(!empty($data)) {
            $form->setValues($data);
        }
       return parent::_prepareForm();
    }
}