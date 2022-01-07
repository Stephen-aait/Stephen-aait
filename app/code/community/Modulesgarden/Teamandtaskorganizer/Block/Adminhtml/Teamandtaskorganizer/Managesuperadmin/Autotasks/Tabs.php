<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-05-08, 14:55:24)
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
class Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_Teamandtaskorganizer_Managesuperadmin_Autotasks_Tabs
        extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('teamandtaskorganizer_autotasks_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('teamandtaskorganizer')->__('Auto Task'));

        // modulesgarden template
        $this->setTemplate('modulesgardenbase/widget/tabs.phtml');
    }

    protected function _beforeToHtml()
    {
        $this->addTab('edit_tasks', array(
            'label' => Mage::helper('teamandtaskorganizer')->__('Details'),
            'content' => $this->getLayout()->createBlock('teamandtaskorganizer/adminhtml_teamandtaskorganizer_managesuperadmin_autotasks_tabs_details')->toHtml(),
        ));

        $this->addTab('task_contitions', array(
            'label' => Mage::helper('teamandtaskorganizer')->__('Conditions'),
            'content' => $this->getLayout()->createBlock('teamandtaskorganizer/adminhtml_teamandtaskorganizer_managesuperadmin_autotasks_tabs_orderconditions')->toHtml(),
        ));

        $this->addTab('task_order_status_conditions', array(
            'label' => Mage::helper('teamandtaskorganizer')->__('Order Status Conditions'),
            'content' => $this->getLayout()->createBlock('teamandtaskorganizer/adminhtml_teamandtaskorganizer_managesuperadmin_autotasks_tabs_orderstatusconditions')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }

}
