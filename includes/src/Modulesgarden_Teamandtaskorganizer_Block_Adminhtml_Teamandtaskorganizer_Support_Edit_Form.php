<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-06-17, 11:17:36)
 * 
 *
 *  CREATED BY MODULESGARDEN       ->        http://modulesgarden.com
 *  CONTACT                        ->       contact@modulesgarden.com
 *
 *
 *
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 *
 *
 * ******************************************************************** */

/**
 * @author Grzegorz Draganik <grzegorz@modulesgarden.com>
 */
class Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_Teamandtaskorganizer_Support_Edit_Form
        extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setUseContainer(true);
        $form->setId('edit_form');
        $this->setForm($form);

        $fieldset = $form->addFieldset('teamandtaskorganizer_form', array('legend' => Mage::helper('teamandtaskorganizer')->__('Details')));

        $fieldset->addField('name', 'text', array(
            'name' => 'name',
            'label' => Mage::helper('teamandtaskorganizer')->__('Name'),
        ));
        $fieldset->addField('email', 'text', array(
            'name' => 'email',
            'label' => Mage::helper('teamandtaskorganizer')->__('Email'),
        ));
        $fieldset->addField('subject', 'text', array(
            'name' => 'subject',
            'label' => Mage::helper('teamandtaskorganizer')->__('Subject'),
        ));
        $fieldset->addField('message', 'editor', array(
            'name' => 'message',
            'label' => Mage::helper('teamandtaskorganizer')->__('Message'),
        ));

        $mailconf = Mage::getStoreConfig('trans_email/ident_general');
        $default_values = array(
            'name' => $mailconf['name'],
            'email' => $mailconf['email'],
            'subject' => 'Question about Team And Task Organizer',
            'message' => '',
        );

        $form->setValues($default_values);

        $form->setAction($this->getUrl('*/*/save'))
                ->setMethod('post');

        $this->setForm($form);
        return parent::_prepareForm();
    }

}
