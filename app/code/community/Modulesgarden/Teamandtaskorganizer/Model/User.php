<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-04-17, 11:10:29)
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

class Modulesgarden_Teamandtaskorganizer_Model_User extends Mage_Core_Model_Abstract {
	
	/**
	 *
	 * @var int
	 */
	protected $_user_id;
	/**
	 *
	 * @var Modulesgarden_Teamandtaskorganizer_Model_Mysql4_User_Privilege_Collection
	 */
	protected $_privilegesCollection;
	/**
	 *
	 * @var Modulesgarden_Teamandtaskorganizer_Model_Mysql4_User_Setting_Collection
	 */
	protected $_settingsCollection;
	/**
	 *
	 * @var Mage_Admin_Model_User
	 */
	protected $_parentObject;
	
	protected $_arrayPrivileges = array('SEE_OTHER_TASK');
	
	
	protected function _construct(){
		$admin = Mage::getSingleton('admin/session')->getUser();
		if ($admin)
			$this->_setup($admin->getUserId());
	}
	
	public function setNewUserId($user_id){
		$this->_setup($user_id);
		return $this;
	}
	
	protected function _setup($user_id){
		$this->_init('teamandtaskorganizer/user');
		
		$this->_user_id = $user_id;
		if (!$this->_user_id)
			throw new Exception('You have to be logged in');
		
		$this->_privilegesCollection = Mage::getModel('teamandtaskorganizer/user_privilege')->getCollection()
			->addFieldToFilter('user_id', $this->_user_id);
		
		$this->_settingsCollection = Mage::getModel('teamandtaskorganizer/user_setting')->getCollection()
			->addFieldToFilter('user_id', $this->_user_id);
	}
	
	public function getUserId(){
		return $this->_user_id;
	}
	
	
	/**
	 * 
	 * @return Mage_Admin_Model_User
	 */
	public function getParentObject(){
		if ($this->_parentObject === null){
			$this->_parentObject = Mage::getModel('admin/user')->load($this->_user_id);
		}
		return $this->_parentObject;
	}
	
	public function isAllowed($privilegeKey){
		foreach ($this->_privilegesCollection as $privilege){
			if ($privilege->getSettingkey() == $privilegeKey){
				if ($privilege->getValue() == -1)
					return $privilege->getValue();
				return in_array($privilegeKey, $this->_arrayPrivileges) ? explode(';;;', $privilege->getValue()) : $privilege->getValue();
			}
		}
		return false;
	}
	
	public function getSetting($settingKey){
		if (!$this->_privilegesCollection)
			throw new Exception('Unable to get setting');
		
		foreach ($this->_settingsCollection as $setting){
			if ($setting->getSettingkey() == $settingKey)
				return $setting->getValue();
		}
		return null;
	}
	
	public function isAllowedToEdit(Varien_Object $item){

		if ($item instanceof Modulesgarden_Teamandtaskorganizer_Model_Task) {
			// own task
			if ($item->getUserId() == $this->_user_id){
				return $this->isAllowed('EDIT_OWN_TASK');
			} else { // other task
				return $this->isAllowed('EDIT_OTHER_TASK');
			}
		}
		if ($item instanceof Modulesgarden_Teamandtaskorganizer_Model_Task_Comment) {
			$task = $item->getTask();
			
			// own task
			if ($task->getUserId() == $this->_user_id) {
				//own comment
				if ($item->getUserId() == $this->_user_id){
					return $this->isAllowed('EDIT_OWN_COMMENT_IN_OWN_TASK');
				} else { // other comment
					return $this->isAllowed('EDIT_OTHER_COMMENT_IN_OWN_TASK');
				}
				
			} else { // other task
				// own comment
				if ($item->getUserId() == $this->_user_id){
					return $this->isAllowed('EDIT_OWN_COMMENT_IN_OTHER_TASK');
				} else { // other comment
					return $this->isAllowed('EDIT_OTHER_COMMENT_IN_OTHER_TASK');
				}
			}
		}
		return false;
	}
	
	public function isAllowedToDelete(Varien_Object $item){
		
		if ($item instanceof Modulesgarden_Teamandtaskorganizer_Model_Task) {
			// own task
			if ($item->getUserId() == $this->_user_id){
				return $this->isAllowed('DELETE_OWN_TASK');
			} else { // other task
				return $this->isAllowed('DELETE_OTHER_TASK');
			}
		}
		if ($item instanceof Modulesgarden_Teamandtaskorganizer_Model_Task_Comment) {
			$task = $item->getTask();
			
			// own task
			if ($task->getUserId() == $this->_user_id) {
				//own comment
				if ($item->getUserId() == $this->_user_id){
					return $this->isAllowed('DELETE_OWN_COMMENT_IN_OWN_TASK');
				} else { // other comment
					return $this->isAllowed('DELETE_OTHER_COMMENT_IN_OWN_TASK');
				}
				
			} else { // other task
				// own comment
				if ($item->getUserId() == $this->_user_id){
					return $this->isAllowed('DELETE_OWN_COMMENT_IN_OTHER_TASK');
				} else { // other comment
					return $this->isAllowed('DELETE_OTHER_COMMENT_IN_OTHER_TASK');
				}
			}
		}
		return false;
	}
	
	public function isAllowedToAddComment(Modulesgarden_Teamandtaskorganizer_Model_Task $task){
		// own task
		if ($task->getUserId() == $this->_user_id) {
			return $this->isAllowed('ADD_COMMENT_OWN_TASK');
		} else { // other task
			return $this->isAllowed('ADD_COMMENT_OTHER_TASK');
		}
		return false;
	}
	
	public function getStatsArray(){
		return $this->getResource()->getStatsArray($this->getUserId());
	}
	
	public function canGetEmail($task_notify){
		$notificationsSetting = $this->getSetting('SEND_NOTIFICATION');
		if (((!$notificationsSetting || $notificationsSetting == 1) && $task_notify) || $notificationsSetting == 2){
			return true;
		}
		return false;
	}
	
	public static function getList($checkPermission, $returnArray = true, array $specificIds = array()){
		$user = Mage::getModel('teamandtaskorganizer/user');
		if ($checkPermission && !$user->isAllowed('ASSIGN_TASK')){
			return $returnArray ?
				array($user->getUserId() => $user->getParentObject()->getLastname() . ' ' . $user->getParentObject()->getFirstname()) :
				Mage::getModel('admin/user')->getCollection()->addFieldToFilter('user_id', $user->getUserId());
		}
		$arr = array();
		$collection = Mage::getModel('admin/user')->getCollection();
		foreach ($collection as $item){
			if ($specificIds && !in_array($item->getId(), $specificIds))
				continue;
			if ($returnArray)
				$arr[$item->getUserId()] = $item->getLastname() . ' ' . $item->getFirstname();
		}
		return $returnArray ? $arr : $collection;
	}
	
}
