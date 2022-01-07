<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */
class Amasty_Orderattach_Block_Adminhtml_Field_Edit_Tab_Main extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
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

    protected function _prepareForm()
    {
        $model = Mage::registry('amorderattach_field');

        $form     = new Varien_Data_Form();
        $fieldset = $form->addFieldset('base_fieldset', array('legend' => Mage::helper('amorderattach')->__('Attachment Field Details'), 'class' => 'fieldset'));

        if ($model->getId()) {
            $fieldset->addField('field_id', 'hidden', array(
                    'name' => 'field_id',
                )
            );
        }

        $yn = array(
            array(
                'value' => '1',
                'label' => $this->__('Yes'),
            ),
            array(
                'value' => '0',
                'label' => $this->__('No'),
            ),
        );

        $codeParams = array(
            'name'     => 'code',
            'label'    => Mage::helper('amorderattach')->__('Alias'),
            'title'    => Mage::helper('amorderattach')->__('Alias'),
            'required' => true,
            'class'    => 'validate-code',
            'note'     => Mage::helper('amorderattach')->__('Please use lowercase letters a-z and _ symbols only.'),
        );
        if ($model->getId()) {
            $codeParams['disabled'] = 'disabled';
        }
        $fieldset->addField('code', 'text', $codeParams);

        $fieldset->addField('label', 'text', array(
                'name'     => 'label',
                'label'    => Mage::helper('amorderattach')->__('Label'),
                'title'    => Mage::helper('amorderattach')->__('Label'),
                'required' => true,
            )
        );

        $typeParams = array(
            'name'               => 'type',
            'label'              => Mage::helper('amorderattach')->__('Field Type'),
            'title'              => Mage::helper('amorderattach')->__('Field Type'),
            'values'             => Mage::helper('amorderattach')->getTypes(false),
            'onchange'           => 'javascript: checkFieldType(this);',
            'after_element_html' => Mage::app()->getLayout()->createBlock('amorderattach/adminhtml_field_edit_options', 'field.options')->setField($model)->toHtml(),
        );

        if ($model->getId()) {
            $typeParams['disabled'] = 'disabled';
            $fieldset->addField('hiddentype', 'hidden', array(
                    'name' => 'hiddentype',
                )
            );
            $model->setHiddenType($model->getType());
        }


        $fieldset->addField('type', 'select', $typeParams);

        $fieldset->addField('new_window', 'select', array(
                'name'   => 'options',
                'label'  => Mage::helper('amorderattach')->__('Open Link In New Window'),
                'title'  => Mage::helper('amorderattach')->__('Open Link In New Window'),
                'values' => $yn,
                'value'  => $model->getOptions(),
            )
        );

        $fieldset->addField('default_value', 'text', array(
                'name'  => 'default_value',
                'label' => Mage::helper('amorderattach')->__('Default Value'),
                'title' => Mage::helper('amorderattach')->__('Default Value'),
                'note'  => Mage::helper('amorderattach')->__('Will be automatically assigned to any new order placed.'),
            )
        );

        $fieldset->addField('customer_visibility', 'select', array(
                'name'   => 'customer_visibility',
                'label'  => Mage::helper('amorderattach')->__('Display To Customer'),
                'title'  => Mage::helper('amorderattach')->__('Display To Customer'),
                'values' => Mage::helper('amorderattach')->getCustomerVisibility(),
            )
        );

        $fieldset->addField('is_enabled', 'select', array(
                'name'   => 'is_enabled',
                'label'  => Mage::helper('amorderattach')->__('Enabled'),
                'title'  => Mage::helper('amorderattach')->__('Enabled'),
                'values' => $yn,
            )
        );

        $fieldset->addField('show_on_grid', 'select', array(
                'name'   => 'show_on_grid',
                'label'  => Mage::helper('amorderattach')->__('Show On Order Grid'),
                'title'  => Mage::helper('amorderattach')->__('Show On Order Grid'),
                'values' => $yn,
            )
        );

        $fieldset->addField('apply_to_each_product', 'select', array(
                'name'   => 'apply_to_each_product',
                'label'  => Mage::helper('amorderattach')->__('Apply To Each Product'),
                'title'  => Mage::helper('amorderattach')->__('Apply To Each Product'),
                'value'  => '0',
                // reversed option array to make "No" option by default (because 'value=>0' don't work actually)
                'values' => array(
                    array(
                        'value' => '0',
                        'label' => $this->__('No'),
                    ),
                    array(
                        'value' => '1',
                        'label' => $this->__('Yes'),
                    )
                )
            )
        );

        $values = array();
        foreach ($model->getData() as $key => $val) {
            $values[$key] = $val;
        }
        $values['hiddentype'] = $model->getHiddenType();

        $form->addValues($values);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}