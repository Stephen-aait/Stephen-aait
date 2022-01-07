<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-05-13, 12:05:12)
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

class Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_Teamandtaskorganizer_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

	public function __construct() {
		parent::__construct();
		$this->setId('teamandtaskorganizer_tabs');
		$this->setTitle(Mage::helper('teamandtaskorganizer')->__('Tasks'));
		
		// modulesgarden template
		$this->setTemplate('modulesgardenbase/widget/tabs.phtml');
	}

	protected function _beforeToHtml() {
		$layout = $this->getLayout();
		
		$this->addTab('tasks_list', array(
			'label' => Mage::helper('teamandtaskorganizer')->__('Your Tasks'),
			'content' => $layout->createBlock('teamandtaskorganizer/adminhtml_teamandtaskorganizer_tasks')->toHtml(),
		));

		if ($this->_canViewStatistics()){
			$this->addTab('statistics', array(
				'label' => Mage::helper('teamandtaskorganizer')->__('Statistics'),
				'content' =>
					$layout->createBlock('teamandtaskorganizer/adminhtml_teamandtaskorganizer_statistics_stats')->toHtml().
					$layout->createBlock('teamandtaskorganizer/adminhtml_teamandtaskorganizer_tasks')->toHtml()
			));
		}

		$this->addTab('settings', array(
			'label' => Mage::helper('teamandtaskorganizer')->__('Settings'),
			'content' => $layout->createBlock('teamandtaskorganizer/adminhtml_teamandtaskorganizer_settings_edit')->toHtml(),
		));

		$this->_updateActiveTab();
		return parent::_beforeToHtml();
	}
	
	protected function _canViewStatistics(){
		$user = Mage::getModel('teamandtaskorganizer/user'); // current user
		$privileges = $user->isAllowed('STATISTICS');
		
		$currentCheckedUser = $this->getRequest()->getParam('user_id');
		if ($currentCheckedUser){
			$user->setNewUserId($currentCheckedUser); // current checked user
		} else {
			$currentCheckedUser = $user->getUserId();
		}
		
		Mage::register('tto_stats_user', $user);
		return $privileges == -1 || $privileges == $currentCheckedUser || (is_array($privileges) && in_array($currentCheckedUser, $privileges));
	}

	protected function _updateActiveTab() {
		$tabId = $this->getRequest()->getParam('tab');
		if ($tabId) {
			$tabId = preg_replace("#{$this->getId()}_#", '', $tabId);
			if ($tabId) {
				$this->setActiveTab($tabId);
			}
		}
	}

}
