<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-05-09, 09:38:03)
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
class Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_Managesuperadmin_Autotasks_Tabs_Orderstatusconditions extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $orderStatuses = $this->getOrderStatuses();
        $form = new Varien_Data_Form();
        
        $autoTask = Mage::getSingleton('teamandtaskorganizer/task_auto');
        
        $fieldset = $form->addFieldset('order_status_conditions_fieldset', array(
            'legend' => Mage::helper('teamandtaskorganizer')->__('Order Status Conditions')
        ));

        $fieldset->addField('primary_order_status', 'select', array(
            'name'      => 'primary_order_status',
            'label'     => Mage::helper('teamandtaskorganizer')->__('Primary Order Status'),
            'title'     => Mage::helper('teamandtaskorganizer')->__('Primary Order Status'),
            'values'    => $orderStatuses,
        ));

        $fieldset->addField('target_order_status', 'select', array(
            'name'      => 'target_order_status',
            'label'     => Mage::helper('teamandtaskorganizer')->__('Target Order Status'),
            'title'     => Mage::helper('teamandtaskorganizer')->__('Target Order Status'),
            'values'    => $orderStatuses,
        ));

        $form->setAction($this->getUrl('*/*/saveConditions'))->setMethod('post');
        
        if( ! $autoTask->isEmpty() AND $autoTask->getOrderStatusConditions() ) {
            $orderStatusConditions = $autoTask->getOrderStatusConditions();
            
            
            
            $form->setValues(array(
                'primary_order_status'  => $orderStatusConditions->getPrimary(),
                'target_order_status'   => $orderStatusConditions->getTarget(),
            ));
        }
        
        $this->setForm($form);

        return parent::_prepareForm();
    }
    
    protected function getOrderStatuses() {
        $orderStatuses  = Mage::getModel('sales/order_status')->getResourceCollection()->getData();
        $values         = array(
            'any'       => Mage::helper('teamandtaskorganizer')->__('Any')
        );
        
        foreach($orderStatuses as $orderStatus) {
            $values[ $orderStatus['status'] ] = $orderStatus['label'];
        }
        
        return $values;
        
    }

}
