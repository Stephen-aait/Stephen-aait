<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-04-16, 14:23:47)
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
class Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_Teamandtaskorganizer_Tasks_Tabs
        extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('teamandtaskorganizer_tasks_tabs');
        $this->setTitle(Mage::helper('teamandtaskorganizer')->__('Task'));

        // modulesgarden template
        $this->setTemplate('modulesgardenbase/widget/tabs.phtml');
    }

    protected function _beforeToHtml()
    {
        $this->addTab('edit_tasks', array(
            'label' => Mage::helper('teamandtaskorganizer')->__('Task Details'),
            'content' => $this->getLayout()->createBlock('teamandtaskorganizer/adminhtml_teamandtaskorganizer_tasks_edit')->toHtml(),
        ));

        $this->addTab('task_comments', array(
            'label' => Mage::helper('teamandtaskorganizer')->__('Comments'),
            'content' => $this->getLayout()->createBlock('teamandtaskorganizer/adminhtml_teamandtaskorganizer_tasks_comments')->toHtml(),
        ));

        $this->_updateActiveTab();
        return parent::_beforeToHtml();
    }

    protected function _updateActiveTab()
    {
        $tabId = $this->getRequest()->getParam('tab');
        if ($tabId) {
            $tabId = preg_replace("#{$this->getId()}_#", '', $tabId);
            if ($tabId) {
                $this->setActiveTab($tabId);
            }
        }
    }

}
