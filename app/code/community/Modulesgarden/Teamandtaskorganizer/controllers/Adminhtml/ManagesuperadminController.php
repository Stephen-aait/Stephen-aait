<?php

/**
 * @author Rafał Samojedny <rafal@modulesgarden.com>
 */
class Modulesgarden_Teamandtaskorganizer_Adminhtml_ManagesuperadminController extends Modulesgarden_Teamandtaskorganizer_Controller_Action {

	/**
	 * Load layout and setmenu to active.
	 *
	 * @return \Modulesgarden_Teamandtaskorganizer_Adminhtml_ManagesuperadminController
	 */
	protected function _initAction() {
		$this->loadLayout()
				->_setActiveMenu('teamandtaskorganizer/teamandtaskorganizer_managesuperadmin');
		return $this;
	}

	protected function _isAllowed() {
		$allowedByMagentoPrivileges = Mage::getSingleton('admin/session')->isAllowed('teamandtaskorganizer/teamandtaskorganizer_managesuperadmin');
		if ($allowedByMagentoPrivileges){
			$user = Mage::getSingleton('teamandtaskorganizer/user');
			if ($user->isAllowed('SEE_OTHER_TASK') || $user->isAllowed('ASSIGN_PRIVILEGES') || $user->isAllowed('ADD_AUTOTASK'))
				return true;
		}
		return false;
	}

	public function indexAction(){
		$user = Mage::getSingleton('teamandtaskorganizer/user');
		$seeOther = $user->isAllowed('SEE_OTHER_TASK');
		if (!$user->isAllowed('ASSIGN_PRIVILEGES') && count($seeOther) < 1){
			Mage::getSingleton('adminhtml/session')->addError($this->__('You are not allowed to see Auto Tasks / Users Privileges'));
			return $this->_redirect('*/adminhtml_task/index');
		}

		Mage::register('tto_grid_onlycurrentuser', false);
		
		include_once Mage::getModuleDir('', 'Modulesgarden_Teamandtaskorganizer') . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR . 'lic.php';
		$license_check = team_and_task_organizer_for_magento_license_3296();
if($license_check['status'] != 'Active'){
    $error_message=isset($license_custom_error_message)?$license_custom_error_message:("License {$license_check['status']}".($license_check['description'] ? ": {$license_check['description']}" : ""));
	
	Mage::getSingleton('adminhtml/session')->addError($error_message);
    Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::helper('adminhtml')->getUrl("adminhtml/dashboard/index"));
	Mage::app()->getResponse()->sendResponse();
	exit;
}
		
		$this->_initAction();
		$this->renderLayout();
	}

//	public function showtabAction() {
//		$this->_initAction();
//		$tab = $this->getRequest()->getParam('tab');
//		if ($tab === 'edit_task') {
//			$action = $this->getRequest()->getParam('task_action', 'edit_task');
//			if ($action === 'edit_comment') {
//				$this->dispatch('editcomment');
//			}
//			if ($action === 'edit_task') {
//				$this->dispatch('edittask');
//			}
//		}
//		if ($tab === 'autotask_edit') {
//			$this->dispatch('editautotask');
//		}
//
//		if ($tab === 'user_privilege') {
//			$this->dispatch('edituserprivilege');
//		}
//		$this->renderLayout();
//	}

	/**
	 * Method for edit or add new task.<br>
	 * If exists request param 'id', then we get data and edit task.<br>
	 *
	 * It also check if we have appropriate privileges to do this action.
	 */
	public function edittaskAction() {
		Mage::register('tto_back_to_managesuperadmin', 1);
		return $this->_forward('edittask', 'adminhtml_task', 'teamandtaskorganizer');
	}

	public function newAction() {
		Mage::register('tto_back_to_managesuperadmin', 1);
		return $this->_forward('new', 'adminhtml_task', 'teamandtaskorganizer');
	}

	/**
	 * Check if we have appropriate privileges and proccess our task data for save.<br>
	 * If post data is null or task end date is earlier than start date, then throws Exception.
	 *
	 * @throws \Mage_Core_Exception
	 * @return \Modulesgarden_Teamandtaskorganizer_Adminhtml_ManagesuperadminController
	 */
//	public function saveAction() {
//		return $this->_forward('save', 'adminhtml_task', 'teamandtaskorganizer');
//	}

	/**
	 * Method for edit or add new comment.<br>
	 * If exists request param 'id', then we get data and edit comment.<br>
	 *
	 * It also check if we have appropriate privileges to do this action.
	 */
	public function editcommentAction() {
		return $this->_forward('editcomment', 'adminhtml_task', 'teamandtaskorganizer');
	}

	/**
	 * Check if we have appropriate privileges and proccess our comment data for save.<br>
	 * If post data is null or task id is not set (for new comment) or comment id is not set (for edit comment), then throws Eception.
	 *
	 * @throws \Mage_Core_Exception
	 * @return \Modulesgarden_Teamandtaskorganizer_Adminhtml_ManagesuperadminController
	 */
	public function saveCommentAction() {
		return $this->_forward('saveComment', 'adminhtml_task', 'teamandtaskorganizer');
	}

	public function editautotaskAction() {
		$user = Mage::getSingleton('teamandtaskorganizer/user');
		$auto_task_id = $this->getRequest()->getParam('id');

		if (!$user->isAllowed('ADD_AUTOTASK')) {
			Mage::getSingleton('adminhtml/session')->addError($this->__('You do not have access for adding Auto Tasks'));
			return $this->_redirect('*/*/index');
		}

		$autoTask = Mage::getSingleton('teamandtaskorganizer/task_auto')
			->load($auto_task_id);

		$rule = Mage::getModel('salesrule/rule');
		if ($autoTask->getConditionsSerialized())
			$rule->setConditionsSerialized($autoTask->getConditionsSerialized());
		Mage::register('current_promo_quote_rule', $rule);

		$this->_initAction();
		$this->renderLayout();
	}

	public function deleteautotaskAction() {
		$user = Mage::getSingleton('teamandtaskorganizer/user');

		if (!$user->isAllowed('ADD_AUTOTASK')) {
			Mage::getSingleton('adminhtml/session')->addError($this->__('You do not have access for adding Auto Tasks'));
			return $this->_redirect('*/*/index');
		}
		$autoTask = Mage::getModel('teamandtaskorganizer/task_auto')->load($this->getRequest()->getParam('id'));
		$autoTask->delete();

		Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Auto Task has been deleted'));
		$this->rautotasksAction();
	}

	public function saveautotaskAction() {
		$post = $this->getRequest()->getPost();
		$user = Mage::getSingleton('teamandtaskorganizer/user');

		if (!$user->isAllowed('ADD_AUTOTASK')) {
			Mage::getSingleton('adminhtml/session')->addError($this->__('You do not have access for adding Auto Tasks'));
			return $this->_redirect('*/*/index');
		}

		$autoTask = Mage::getModel('teamandtaskorganizer/task_auto');
		if ($post['id'])
			$autoTask->setId($post['id']);
		else
			$autoTask->setEvent($post['event']);

		$autoTask->setUserId($post['user_id']);
		$autoTask->setTitle($post['title']);
		$autoTask->setDescription($post['description']);
		$autoTask->setPriority($post['priority']);
		$autoTask->setStatus($post['status']);
		$autoTask->setNotify(isset($post['notify']) ? 1 : 0);

		if (isset($post['event']) && $post['event'] == 'after_order') {
			$model = Mage::getModel('salesrule/rule');
			$validateResult = $model->validateData(new Varien_Object($post));
			if ($validateResult !== true) {
				foreach ($validateResult as $errorMessage)
					Mage::getSingleton('adminhtml/session')->addError($errorMessage);

				$this->_redirect('*/*/edit', array('id' => $model->getId()));
				return;
			}
			if (isset($post['rule']['conditions'])) {
				$post['conditions'] = $post['rule']['conditions'];
			}
			unset($post['rule']);
			$model->loadPost($post);
			$conditions_serialized = serialize($model->getConditions()->asArray());

			$autoTask->setConditionsSerialized($conditions_serialized);
		}
                
                $autoTask->unsetOrderStatusConditions();
                
                if (isset($post['event']) && $post['event'] == 'after_changed_order_status') {
                    $orderStatusConditions = new Modulesgarden_Teamandtaskorganizer_Model_OrderConditions($post['primary_order_status'], $post['target_order_status']);
                    $autoTask->setOrderStatusConditions($orderStatusConditions);
		}

		$autoTask->save();

		Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('teamandtaskorganizer')->__('Auto Task has been saved'));

		if ($autoTask->getId()) {
			return $this->_redirect('teamandtaskorganizer/adminhtml_managesuperadmin/index', array(
				'tab' => 'teamandtaskorganizer_managesuperadmin_tabs_auto_tasks'
			));
		}

		$this->rautotasksAction();
	}

	/**
	 * Method for edit user privileges.<br>
	 * If exists request param 'id', then we get data and edit privileges.<br>
	 *
	 * It also check if we have appropriate privileges ('ASSIGN_PRIVILEGES') to do this action.
	 * @return \Modulesgarden_Teamandtaskorganizer_Adminhtml_ManagesuperadminController
	 */
	public function edituserprivilegeAction() {
		$user_id = $this->getRequest()->getParam('id');
		$user = Mage::getModel('teamandtaskorganizer/user');

		if (!$user->isAllowed('ASSIGN_PRIVILEGES')) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('teamandtaskorganizer')->__("You do not have persmissions to assign privileges"));
			return $this->_redirect('*/*/index');
		}

		$user->setNewUserId($user_id);
		Mage::register('tto_privileges_user', $user);

		$this->_initAction();
		$this->renderLayout();
	}

	/**
	 * Check if we have appropriate privilege ('ASSIGN_PRIVILEGES') and proccess our privileges data for save.<br>
	 * If post data is null then throws Eception.
	 *
	 * @throws \Mage_Core_Exception
	 * @return \Modulesgarden_Teamandtaskorganizer_Adminhtml_ManagesuperadminController
	 */
	public function saveuserprivilegeAction() {
		$user = Mage::getSingleton('teamandtaskorganizer/user'); // current logged in user
		$post = $this->getRequest()->getPost();
		$user_id = $post['user_id'];
		$current_user_id = $user->getUserId();
		$permissionsMap = Mage::getModel('teamandtaskorganizer/user_privilege')->getPermissionsMap();

		if (!$user->isAllowed('ASSIGN_PRIVILEGES')) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('teamandtaskorganizer')->__("You don't have persmissions to assign privileges"));
			return $this->_redirect('*/*/index');
		}

		try {

			$user->setNewUserId($user_id); // edited user
			if (empty($post))
				throw new Exception($this->__('Invalid form data.'));

			Mage::getResourceModel('teamandtaskorganizer/user_privilege')->deleteByUserId($user_id);

			if ($current_user_id == $user_id){
				$post['ASSIGN_PRIVILEGES'] = 1;
			}
			
			foreach ($permissionsMap as $group) {
				foreach ($group as $permId => $permName) {
					if (!isset($post[$permId]) || !$permId)
						continue;

					// user edit himself AND now is ASSIGN_PRIVILEGES --> do not change anything
//					if ($current_user_id == $user_id && $permId == 'ASSIGN_PRIVILEGES') {
//						continue;
//					}
					
					// ASSIGN_PRIVILEGES 

					$value = 1;
					if ($permId === 'SEE_OTHER_TASK' || $permId === 'STATISTICS') {
						if (is_array($post[$permId]) && in_array(-1, $post[$permId]))
							$value = -1;
						else
							$value = implode(';;;', $post[$permId]);
					}

					$privilege = Mage::getModel('teamandtaskorganizer/user_privilege');
					$privilege->setUserId($user_id);
					$privilege->setSettingkey($permId);
					$privilege->setValue($value);
					$privilege->save();

//					$collection = Mage::getModel('teamandtaskorganizer/user_setting')->getCollection()
//						->addFieldToFilter('user_id', $user_id)
//						->addFieldToFilter('settingkey', $permId);
//
//					if ($collection->getSize()){ // config already exists
//						foreach ($collection as $privilege){
//							$privilege->setValue($value);
//							$privilege->save();
//						}
//
//					} else {
//						$p = Mage::getModel('teamandtaskorganizer/user_privilege')
//							->setUserId($user_id)
//							->setSettingkey($permId)
//							->setValue($value);
//						$p->save();
//					}
				}
			}

			Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Permissions have been saved successfully.'));
		} catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		return $this->rusersAction();
	}

	public function rusersAction() {
		return $this->_redirect('teamandtaskorganizer/adminhtml_managesuperadmin/index', array('tab' => 'teamandtaskorganizer_managesuperadmin_tabs_user_privileges'));
	}

	public function rautotasksAction() {
		return $this->_redirect('teamandtaskorganizer/adminhtml_managesuperadmin/index', array('tab' => 'teamandtaskorganizer_managesuperadmin_tabs_auto_tasks'));
	}

	public function rstatsAction() {
		return $this->_redirect('teamandtaskorganizer/adminhtml_managesuperadmin/index', array('tab' => 'teamandtaskorganizer_managesuperadmin_tabs_statistics'));
	}

}
