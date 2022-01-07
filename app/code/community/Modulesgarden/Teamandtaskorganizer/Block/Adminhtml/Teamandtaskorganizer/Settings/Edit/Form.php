<?php

class Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_Teamandtaskorganizer_Settings_Edit_Form
        extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $h = Mage::helper('teamandtaskorganizer');
        $user = Mage::getSingleton('teamandtaskorganizer/user');

        $form = new Varien_Data_Form();
        $form->setUseContainer(true);
        $form->setId('settings_form');
        $this->setForm($form);
        $fieldset = $form->addFieldset('teamandtaskorganizer_settings_form', array(
            'legend' => $h->__('Edit Settings')
        ));

        $fieldset->addField('WIDGET_PLACEMENT', 'select', array(
            'name' => 'WIDGET_PLACEMENT',
            'label' => $h->__('Task List Widget Position'),
            'values' => array(
                Modulesgarden_Teamandtaskorganizer_Model_Widget::PLACEMENT_BOTTOM => $h->__('Bottom'),
                Modulesgarden_Teamandtaskorganizer_Model_Widget::PLACEMENT_RIGHT => $h->__('Right'),
                Modulesgarden_Teamandtaskorganizer_Model_Widget::PLACEMENT_CORNER => $h->__('Corner (Live chat style)')
            ),
            'value' => Modulesgarden_Teamandtaskorganizer_Model_Widget::getDefaultPosition()
        ));

        $fieldset->addField('SEND_NOTIFICATION', 'select', array(
            'name' => 'SEND_NOTIFICATION',
            'label' => $h->__('Send Notification'),
            'values' => array(
                0 => $h->__('Never'),
                1 => $h->__('When task has option enabled'),
                2 => $h->__('Always'),
            ),
            'value' => 1
        ));

        $fieldset->addField('REFRESH_FREQUENCY', 'text', array(
            'name' => 'REFRESH_FREQUENCY',
            'label' => $h->__('Task List Refresh Frequency Time(s)'),
            'value' => '20'
        ));

        $form->setAction($this->getUrl('*/teamandtaskorganizer_settings/save'))
                ->setMethod('post');

        $data = array(
            'WIDGET_PLACEMENT' => Modulesgarden_Teamandtaskorganizer_Model_Widget::getDefaultPosition(),
            'SEND_NOTIFICATION' => 1,
            'REFRESH_FREQUENCY' => 20,
        );
        if ($user->getSetting('WIDGET_PLACEMENT'))
            $data['WIDGET_PLACEMENT'] = $user->getSetting('WIDGET_PLACEMENT');
        if ($user->getSetting('SEND_NOTIFICATION'))
            $data['SEND_NOTIFICATION'] = $user->getSetting('SEND_NOTIFICATION');
        if ($user->getSetting('REFRESH_FREQUENCY'))
            $data['REFRESH_FREQUENCY'] = $user->getSetting('REFRESH_FREQUENCY');

        $form->setValues($data);
        $this->setForm($form);
        return parent::_prepareForm();
    }

}
