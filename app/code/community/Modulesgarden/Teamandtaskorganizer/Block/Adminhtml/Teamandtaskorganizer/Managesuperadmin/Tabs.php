<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-05-14, 09:38:58)
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
class Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_Teamandtaskorganizer_Managesuperadmin_Tabs
        extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('teamandtaskorganizer_managesuperadmin_tabs');
        $this->setTitle(Mage::helper('teamandtaskorganizer')->__('Administration'));

        // modulesgarden template
        $this->setTemplate('modulesgardenbase/widget/tabs.phtml');
    }

    protected function _beforeToHtml()
    {
        $user = Mage::getSingleton('teamandtaskorganizer/user');

        if ($user->isAllowed('SEE_OTHER_TASK')) {
            $this->addTab('tasks_list', array(
                'label' => Mage::helper('teamandtaskorganizer')->__('Tasks List'),
                'content' => $this->getLayout()->createBlock('teamandtaskorganizer/adminhtml_teamandtaskorganizer_tasks')->toHtml(),
            ));
        }

        if ($user->isAllowed('ASSIGN_PRIVILEGES')) {
            $this->addTab('user_privileges', array(
                'label' => Mage::helper('teamandtaskorganizer')->__('User Privileges'),
                'content' => $this->getLayout()->createBlock('teamandtaskorganizer/adminhtml_teamandtaskorganizer_managesuperadmin_users')->toHtml(),
            ));
        }

        if ($user->isAllowed('ADD_AUTOTASK')) {
            $this->addTab('auto_tasks', array(
                'label' => Mage::helper('teamandtaskorganizer')->__('Auto Tasks'),
                'content' => $this->getLayout()->createBlock('teamandtaskorganizer/adminhtml_teamandtaskorganizer_managesuperadmin_autotasks')->toHtml(),
            ));
        }

//		if ($user->isAllowed('STATISTICS') == '-1'){
//			$this->addTab('statistics', array(
//				'label' => Mage::helper('teamandtaskorganizer')->__('Statistics'),
//				'content' => 'TODO',//$this->getLayout()->createBlock('teamandtaskorganizer/adminhtml_statistics_stats')->toHtml(),
//			));
//		}

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
