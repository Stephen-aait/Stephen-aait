<?php

/**
 * @author Rafał Samojedny <rafal@modulesgarden.com>
 */

class Modulesgarden_Teamandtaskorganizer_Adminhtml_StatisticsController extends Modulesgarden_Teamandtaskorganizer_Controller_Action {

	protected function _isAllowed(){
		$user = Mage::getSingleton('teamandtaskorganizer/user');
		$privileges = $user->isAllowed('STATISTICS');
		
		$currentCheckedUser = $this->getRequest()->getParam('user_id');
		if (!$currentCheckedUser){
			$currentCheckedUser = $user->getUserId();
			$user->setNewUserId($currentCheckedUser);
		}
		
		Mage::register('tto_stats_user', $user);
		return $privileges === -1 || (is_array($privileges) && in_array($currentCheckedUser, $privileges));
	}
	
	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('teamandtaskorganizer/teamandtaskorganizer_statistics');
		return $this;
	}

	public function indexAction() {
		$this->_initAction();
		$this->renderLayout();
	}

}
