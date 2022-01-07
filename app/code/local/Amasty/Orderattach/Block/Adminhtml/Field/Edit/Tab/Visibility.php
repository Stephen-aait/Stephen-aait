<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */
class Amasty_Orderattach_Block_Adminhtml_Field_Edit_Tab_Visibility extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected function _prepareForm()
    {
        $model = Mage::registry('amorderattach_field');

        $form = new Varien_Data_Form();
        $fieldset = $form->addFieldset('visibility_fieldset', array('legend'=>Mage::helper('amorderattach')->__('Order Status Visibility'), 'class' => 'fieldset'));
        
        if ($model->getId()) {
            $fieldset->addField('field_id', 'hidden', array(
                'name' => 'field_id',
            ));
        }
        
        $statuses = Mage::getSingleton('sales/order_config')->getStatuses();
        $values   = array();
        $values[] = array('value' => 'all', 'label' => 'All');
        foreach ($statuses as $code => $name)
        {
            $values[] = array('value' => $code, 'label' => $name);
        }
        
        $fieldset->addField('status_backend', 'multiselect', array(
            'name'      => 'status_backend',
            'label'     => Mage::helper('amorderattach')->__('Order Status For Backend'),
            'title'     => Mage::helper('amorderattach')->__('Order Status For Backend'),
            'values'    => $values,
            'note'      => $this->__('Display field only if order status is one of the selected'),
        ));
        $fieldset->addField('status_frontend', 'multiselect', array(
                    'name'      => 'status_frontend',
                    'label'     => Mage::helper('amorderattach')->__('Order Status For Frontend'),
                    'title'     => Mage::helper('amorderattach')->__('Order Status For Frontend'),
                    'values'    => $values,
                    'note'      => $this->__('Display field only if order status is one of the selected'),
         ));

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }
    
    public function getTabLabel()
    {
        return Mage::helper('amorderattach')->__('Field Information');
    }
    
    public function getTabTitle()
    {
        return Mage::helper('amorderattach')->__('Field Information');
    }
    
    public function canShowTab()
    {
        return true;
    }
    
    public function isHidden()
    {
        return false;
    }
}