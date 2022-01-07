<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-06-12, 10:42:25)
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
class Modulesgarden_Teamandtaskorganizer_Adminhtml_TaskController extends Modulesgarden_Teamandtaskorganizer_Controller_Action {

	protected function _initAction() {
		$this->loadLayout()->_setActiveMenu('teamandtaskorganizer/teamandtaskorganizer_task');
		return $this;
	}
	
	protected function _isAllowed(){
		return Mage::getSingleton('admin/session')->isAllowed('teamandtaskorganizer/teamandtaskorganizer_task');
	}

	public function renderAjaxLayout() {
		$blocks = $this->getLayout()->getBlock('content');
		$blocks->unsetChild('default_task_list');
		if ($this->getRequest()->getActionName() == 'edittask') {
			$blocks->unsetChild('teamandtaskorganizer_adminhtml_task_comment_grid');
		}
		echo $blocks->toHtml();
		exit;
	}

	public function indexAction() {
		if (Mage::registry('tto_grid_onlycurrentuser') === null)
			Mage::register('tto_grid_onlycurrentuser', true);
		
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

	public function newAction() {
		$user = Mage::getSingleton('teamandtaskorganizer/user');
		
		if ($this->getRequest()->isAjax()){
			Mage::register('tto_ajax_adding_new_task', 1);
			echo $this->getLayout()->createBlock('teamandtaskorganizer/adminhtml_tasks_edit_form')->toHtml();
			die();
		}
		
		if (!$user->isAllowed('ADD_TASK')){
			Mage::getSingleton('adminhtml/session')->addError($this->__('You do not have access for adding new task'));
			return $this->_redirect('teamandtaskorganizer/adminhtml_task/index');
		}
		
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
	
	public function viewtaskAction() {
		$id = $this->getRequest()->getParam('id', 0);
		$user = Mage::getSingleton('teamandtaskorganizer/user');
		$task = Mage::getSingleton('teamandtaskorganizer/task')
			->load($id);
		
		
		if ($task->isEmpty()){
			echo $this->__('It looks like task #%d does not exists!', $id);
			die();
		}
		if (!$this->getRequest()->isAjax()){
			echo $this->__('Application error');
			die();
		}
		if (!$user->isAllowedToEdit($task) && $user->getUserId() != $task->getUserId()){
			echo $this->__('You do not have access to editing task #%d', $id);
			die();
		}
		
		// if owner AND unread -> mark task as read
		if ($user->getUserId() == $task->getUserId() && !$task->getReadByOwner()){
			$task->setReadByOwner(1);
			$task->save();
		}
		
		echo $this->getLayout()->createBlock('teamandtaskorganizer/adminhtml_tasks_view')->toHtml();
		die();
	}
	
	public function edittaskAction() {
		$id = $this->getRequest()->getParam('id', 0);
		$user = Mage::getSingleton('teamandtaskorganizer/user');
		$task = Mage::getSingleton('teamandtaskorganizer/task')
			->load($id);
		
		
		if ($task->isEmpty()){
			Mage::getSingleton('adminhtml/session')->addError($this->__('It looks like task #%d does not exists!', $id));
			return $this->_redirect('teamandtaskorganizer/adminhtml_task/index');
		}
		
		// if owner AND unread -> mark task as read
		if ($user->getUserId() == $task->getUserId() && !$task->getReadByOwner()){
			$task->setReadByOwner(1);
			$task->save();
		}
		
		if (!$user->isAllowedToEdit($task)){
			Mage::getSingleton('adminhtml/session')->addError($this->__('You do not have access to editing task #%d', $id));
			return $this->_redirect('teamandtaskorganizer/adminhtml_task/index');
		}
		
		if ($this->getRequest()->isAjax()){
			echo $this->getLayout()->createBlock('teamandtaskorganizer/adminhtml_tasks_edit_form')->toHtml();
			die();
		}
		
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

	public function saveAction() {
		$post		= $this->getRequest()->getPost();
		$id			= isset($post['task_id']) ? (int)$post['task_id'] : null;
		$user		= Mage::getSingleton('teamandtaskorganizer/user');
		$response	= array(
			'success'   => true,
			'message'   => '',
                        'task'      => new stdClass,
		);
		
		try {
			if (empty($post)) {
				Mage::throwException($this->__('Invalid form data.'));
			}
			if (!empty($post['startdate']) && !empty($post['enddate'])) {
				if (strtotime($post['startdate']) > strtotime($post['enddate'])) {
					Mage::throwException($this->__('Task end date cannot be earlier than start date.'));
				}
			}
			
			include_once Mage::getModuleDir('', 'Modulesgarden_Teamandtaskorganizer') . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR . 'lic.php';
			$license_check = team_and_task_organizer_for_magento_license_3296();
if($license_check['status'] != 'Active'){
    $error_message=isset($license_custom_error_message)?$license_custom_error_message:("License {$license_check['status']}".($license_check['description'] ? ": {$license_check['description']}" : ""));
	
	Mage::getSingleton('adminhtml/session')->addError($error_message);
    Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::helper('adminhtml')->getUrl("adminhtml/dashboard/index"));
	Mage::app()->getResponse()->sendResponse();
	exit;
}
			
			$task = Mage::getModel('teamandtaskorganizer/task')
				->load($id);
			
			if (!$task->isEmpty()){
				if (!$user->isAllowedToEdit($task)) {
					Mage::getSingleton('adminhtml/session')->addError(Mage::helper('teamandtaskorganizer')->__("You don't have persmissions to edit '%s'", $task->getTitle()));
					return $this->_redirect('*/*/index');
				}
			} elseif (!$user->isAllowed('ADD_TASK')) {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('teamandtaskorganizer')->__("You don't have persmissions to add new task"));
				return $this->_redirect('*/*/index');
			}
			
			$notify = isset($post['notify']) ? Modulesgarden_Teamandtaskorganizer_Model_Task::NOTIFY_ENABLED : Modulesgarden_Teamandtaskorganizer_Model_Task::NOTIFY_DISABLED;
			
			$task->setUserId( $user->isAllowed('ASSIGN_TASK') && isset($post['user_id']) ? $post['user_id'] : $user->getUserId() );
			$task->setCrdate( date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time())) );
			$task->setUpdate( date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time())) );
			$task->setTitle( $post['title'] );
			$task->setDescription( $post['description'] );
			$task->setPriority( $post['priority'] );
			$task->setProgress( $post['progress'] > 100 ? 100 : (int)$post['progress'] );
			$task->setStatus( $post['status'] );
			$task->setNotify( $notify );
			if ($post['startdate'])
				$task->setStartdate( date('Y-m-d H:i:s', strtotime($post['startdate'])) );
			if ($post['enddate'])
				$task->setEnddate( date('Y-m-d H:i:s', strtotime($post['enddate'])) );
			
			// @todo additional fields in the form?
			// foreign_id	foreign_type	
			
			if ($task->isEmpty()){
				$task->setCreatorId( $user->getUserId() );
				$task->setType(Modulesgarden_Teamandtaskorganizer_Model_Task::TYPE_NORMAL);
			}

			$task->save();
			
			$message = $this->__('Task was successfully saved.');
                        $response['task'] = $task->getDataForUser($user);
                        
			if (!$id) {
				$id = $task->getId();
				$message = $this->__('Task was successfully created.');
				$response['id'] = $id;
				$response['access'] = array(
					'edit' => $user->isAllowedToEdit($task),
					'delete' => $user->isAllowedToDelete($task)
				);
			}

			// @todo do it in user class
			$user->setNewUserId($task->getUserId());
			if ($user->canGetEmail($notify)){
				$task->sendNotificationToOwner();
			}
			
			if ($this->getRequest()->isAjax()) {
				$response['message'] = $message;
				$this->toAjaxResponse($response);
			}
			Mage::getSingleton('adminhtml/session')->addSuccess($message);
			
		} catch (Exception $e) {
			if ($this->getRequest()->isAjax()) {
				$response['message'] = $e->getMessage();
				$this->toAjaxResponse($response);
			}
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			$this->_redirect('*/*');
		}
		if (isset($post['back'])) {
			$this->_redirect($post['back']);
		} else {
			$this->_redirect('*/*');
		}
	}
	
	public function deleteAction() {
		$isAjax		= $this->getRequest()->isAjax();
		$id			= $this->getRequest()->getParam('id');
		$user		= Mage::getSingleton('teamandtaskorganizer/user');
		$response = array(
			'success' => true,
			'message' => ''
		);
		try {
			$task = Mage::getModel('teamandtaskorganizer/task')
				->load($id);
			
			if ($task->isEmpty()) {
				Mage::throwException($this->__('Invalid task id.'));
			}
			if ($user->isAllowedToDelete($task)) {
				$task->delete();

				$message = $this->__('Task has been successfully deleted.');
				$response['message'] = $message;
				if (!$isAjax) {
					Mage::getSingleton('adminhtml/session')->addSuccess($message);
				}
			}
		} catch (Exception $e) {
			$response['message'] = $e->getMessage();
			if (!$isAjax) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}
		if ($isAjax) {
			return $this->toAjaxResponse($response);
		}
		$backUrl = $this->getRequest()->getParam('backUrl');
		if ($backUrl == 'referer') {
			return $this->_redirectUrl($this->getRequest()->getServer('HTTP_REFERER'));
		} elseif (strlen($backUrl)) {
			return $this->_redirectUrl('http://' . str_replace('|', '/', $backUrl));
		}
		$this->_redirect('*/*');
	}

	public function editcommentAction() {
		$id			= $this->getRequest()->getParam('id');
		$task_id	= $this->getRequest()->getParam('task_id');
		$user		= Mage::getSingleton('teamandtaskorganizer/user');
		$task		= Mage::getSingleton('teamandtaskorganizer/task')->load($task_id);
		$comment	= Mage::getSingleton('teamandtaskorganizer/task_comment');
		
		if ($id){
			$comment->load($id);
			if ($comment->isEmpty()){
				Mage::getSingleton('adminhtml/session')->addError($this->__('It looks like comment #%d does not exists!', $id));
				return $this->_redirect('teamandtaskorganizer/adminhtml_task/index');
			}
			if (!$user->isAllowedToEdit($comment)) {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('teamandtaskorganizer')->__("You don't have persmissions to edit comment #%d", $comment->getId()));
				return $this->_redirect('teamandtaskorganizer/adminhtml_task/index');
			}
			
		} else {
			if (!$user->isAllowedToAddComment($task)){
				die('asdasda');
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('teamandtaskorganizer')->__("You don't have persmissions to add new comment to this task"));
				return $this->_redirect('teamandtaskorganizer/adminhtml_task/index');
			}
		}
		
		$this->_initAction();
		$this->renderLayout();
	}
	
	public function saveCommentAction() {
		$isAjax		= $this->getRequest()->isAjax();
		$post		= $this->getRequest()->getPost();
		$taskId		= $this->getRequest()->getParam('task_id');
		$commentId	= $this->getRequest()->getParam('comment_id');
		$user		= Mage::getSingleton('teamandtaskorganizer/user');
		$response	= array(
			'success' => true,
			'message' => ''
		);
		try {
			if (empty($post))
				Mage::throwException($this->__('Invalid form data.'));
			if (!$taskId)
				Mage::throwException($this->__('No task id set'));
			
			$task	= Mage::getModel('teamandtaskorganizer/task')->load($taskId);
			$comment= Mage::getModel('teamandtaskorganizer/task_comment')->load($commentId);
			
			if ($task->isEmpty())
				Mage::throwException($this->__('Task does not exist'));
			
			if (!$comment->isEmpty()) { // edit
				
				if (!$user->isAllowedToEdit($comment)) {
					Mage::getSingleton('adminhtml/session')->addError(Mage::helper('teamandtaskorganizer')->__("You don't have persmissions to edit comment #%d", $comment->getId()));
					if (!$isAjax) {
						return $this->_redirect('*/*/index');
					}
					Mage::throwException($this->__("You don't have persmissions to edit comment #%d", $comment->getId()));
				}
			} else { // adding new one
				if (!$user->isAllowedToAddComment($task)) {
					Mage::getSingleton('adminhtml/session')->addError(Mage::helper('teamandtaskorganizer')->__("You don't have persmissions to add new comment to this task"));
					if (!$isAjax) {
						return $this->_redirect('*/*/index');
					}
					Mage::throwException($this->__("You don't have permissions to add new comment to this task"));
				}
				$comment->setTaskId($task->getId());
				$comment->setUserId($user->getUserId());
				$comment->setCrdate(date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time())));
			}
			
			$comment->setContent(nl2br(strip_tags($post['content'])));
			$comment->save();
                        
                        $users = Modulesgarden_Teamandtaskorganizer_Model_User::getList(false);
                        
                        $response['comment'] = $comment->getData();
                        $response['comment']['user'] = $users[$comment->getUserId()];
			$response['task'] = $task->getDataForUser($user);
			// @todo do it in user class
			$user->setNewUserId($task->getUserId());
			$notificationsSetting = $user->getSetting('SEND_NOTIFICATION');
			if (((!$notificationsSetting || $notificationsSetting == 1) && $task->getNotify()) || $notificationsSetting == 2){
				$comment->sendNotificationToOwner();
			}

			$response['message'] = $this->__('Your comment for task #' . $comment->getTaskId() . ' has been saved successfully.');
			if (!$isAjax)
				Mage::getSingleton('adminhtml/session')->addSuccess($response['message']);
			
		} catch (Exception $e) {
			if (!$isAjax) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/editcomment', array('task_id' => $taskId, 'id' => $commentId));
			} else {
				$response['success'] = false;
				$response['message'] = $e->getMessage();
			}
		}
		if (!$isAjax) {
			$this->_redirect('teamandtaskorganizer/adminhtml_task/edittask', array(
				'id' => $taskId,
				'tab' => 'teamandtaskorganizer_tasks_tabs_task_comments'
			));
		} else {
			return $this->toAjaxResponse($response);
		}
	}

	public function deleteCommentAction() {
		$user		= Mage::getSingleton('teamandtaskorganizer/user');
		$commentId	= $this->getRequest()->getParam('id');
		$comment	= Mage::getModel('teamandtaskorganizer/task_comment')->load($commentId);
		
		try {
			if ($comment->isEmpty() || !$user->isAllowedToDelete($comment))
				Mage::throwException($this->__('You are noe allowed to delete this comment.'));
			
			$comment->delete();

			$message = $this->__('Comment successfully deleted.');
			Mage::getSingleton('adminhtml/session')->addSuccess($message);
				
		} catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		$backUrl = $this->getRequest()->getParam('backUrl');
		if ($backUrl == 'referer') {
			return $this->_redirectUrl($this->getRequest()->getServer('HTTP_REFERER'));
		} elseif (strlen($backUrl)) {
			return $this->_redirectUrl('http://' . str_replace('|', '/', $backUrl));
		}
		$this->_redirect('teamandtaskorganizer/adminhtml_task/edittask', array(
			'id' => $comment->getTask()->getId(),
			'tab' => 'teamandtaskorganizer_tasks_tabs_task_comments'
		));
	}

//	public function massDeleteAction() {
//		$isAjax = $this->getRequest()->isAjax();
//		$response = array(
//			'success' => true,
//			'message' => '',
//			'deleted' => array()
//		);
//		if ($this->getRequest()->isPost()) {
//			$post = $this->getRequest()->getPost();
//			$counter = 0;
//			try {
//				foreach ($post['teamandtaskorganizer'] as $id) {
//					$task = new TTOrganizer_Model_Task();
//					$task->setCrud(Mage::getModel('teamandtaskorganizer/system'));
//					$task->fetch($id);
//					if (Mage::helper('teamandtaskorganizer')->isAllowToDelete($task)) {
//						$task->delete();
//						$response['deleted'][] = $task->getId();
//						$counter++;
//					}
//					unset($task);
//				}
//				unset($id);
//				$message = $this->__('Deleted %d tasks.', $counter);
//				$response['message'] = $message;
//				if (!$isAjax) {
//					Mage::getSingleton('adminhtml/session')->addSuccess($message);
//				}
//			} catch (Exception $e) {
//				$response['message'] = $e->getMessage();
//				if (!$isAjax) {
//					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
//				}
//			}
//		}
//		if ($isAjax) {
//			return $this->toAjaxResponse($response);
//		}
//		$this->_redirectUrl($this->getRequest()->getServer('HTTP_REFERER'));
//	}
//
//	public function massStatusAction() {
//		if ($this->getRequest()->isPost()) {
//			$post = $this->getRequest()->getPost();
//			try {
//				foreach ($post['teamandtaskorganizer'] as $id) {
//					$task = new TTOrganizer_Model_Task();
//					$task->setCrud(Mage::getModel('teamandtaskorganizer/system'));
//					$task->fetch($id);
//					if (Mage::helper('teamandtaskorganizer')->isAllowToEdit($task)) {
//						$task->setStatus($post['status']);
//						$task->save();
//					}
//					unset($task);
//				}
//				unset($id);
//				$message = $this->__('Status changed successfully.');
//				Mage::getSingleton('adminhtml/session')->addSuccess($message);
//			} catch (Exception $e) {
//				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
//			}
//		}
//		$this->_redirectUrl($this->getRequest()->getServer('HTTP_REFERER'));
//	}
//
//	public function massPriorityAction() {
//		if ($this->getRequest()->isPost()) {
//			$post = $this->getRequest()->getPost();
//			try {
//				foreach ($post['teamandtaskorganizer'] as $id) {
//					$task = new TTOrganizer_Model_Task();
//					$task->setCrud(Mage::getModel('teamandtaskorganizer/system'));
//					$task->fetch($id);
//					if (Mage::helper('teamandtaskorganizer')->isAllowToEditPriority($task)) {
//						$task->setPriority($post['priority']);
//						$task->save();
//					}
//					unset($task);
//				}
//				unset($id);
//				$message = $this->__('Priority changed successfully.');
//				Mage::getSingleton('adminhtml/session')->addSuccess($message);
//			} catch (Exception $e) {
//				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
//			}
//		}
//		$this->_redirectUrl($this->getRequest()->getServer('HTTP_REFERER'));
//	}
//
	public function taskListAction() {
		$user = Mage::getSingleton('teamandtaskorganizer/user');
		
		$limit		= $this->getRequest()->getParam('limit', 10);
		$data		= array(
			'success'			=> true,
			'tasks'				=> array(),
			'total'				=> 0,
			'perm_editOwnTask'	=> (int)$user->isAllowed('EDIT_OWN_TASK')
		);
		
		$collection = Mage::getModel('teamandtaskorganizer/task')->getCollection()
			->addFieldToFilter('user_id', $user->getUserId())
			->addFieldToFilter('status', array('neq' => Modulesgarden_Teamandtaskorganizer_Model_Task::STATUS_DONE))
			->setCurPage(1)
			->setPageSize($limit);
		
		$collection->getSelect()
			->order('priority DESC')
			->order('status DESC');

		$total = 0;
		foreach ($collection as $item) {
			$data['tasks'][] = $item->getDataForUser($user);
			$total++;
		}
		$data['total'] = $total;
		return $this->toAjaxResponse($data);
	}
	
	public function rstatisticsAction(){
		return $this->_redirect('teamandtaskorganizer/adminhtml_task/index', array( 'tab' => 'teamandtaskorganizer_tabs_statistics' ));
	}
	
	public function rsettingsAction(){
		return $this->_redirect('teamandtaskorganizer/adminhtml_task/index', array( 'tab' => 'teamandtaskorganizer_tabs_settings' ));
	}
	

	public function updateStatusAction() {
		$user = Mage::getSingleton('teamandtaskorganizer/user');
		$taskId = $this->getRequest()->getParam('task_id');
		$data = array(
			'success' => true,
			'message' => '',
		);
		
		try {
			$task = Mage::getModel('teamandtaskorganizer/task')
				->load($taskId);
			
			if ($task->isEmpty())
				throw new Exception($this->__('No such task.'));
			
			// if allowed by permissions or owner
			if ($user->isAllowedToEdit($task) || $user->getUserId() == $task->getUserId()){
				
				// if owner AND unread -> mark task as read
				if ($user->getUserId() == $task->getUserId() && !$task->getReadByOwner()){
					$task->setReadByOwner(1);
				}
				$progress = $this->getRequest()->getParam('progress', $task->getProgress());
				if ($progress)
					$task->setProgress($progress > 100 ? 100 : $progress);
				
				$task->setStatus($this->getRequest()->getParam('status', $task->getStatus()));
				$task->save();
				
				
				$statuses = $task->getStatusMap();
                                $data['task'] = $task->getDataForUser($user);
				$data['message'] = $this->__($statuses[$task->getStatus()]);
			}
                        else {
                            $data['task'] = $task->getDataForUser($user);
                        }
			
		} catch (Exception $e){
			$data['success'] = false;
			$data['message'] = $e->getMessage();
		}
		
		return $this->toAjaxResponse($data);
	}

	public function getCommentsAction() {
            $user   = Mage::getSingleton('teamandtaskorganizer/user');
            $taskId = (int) $this->getRequest()->getParam('task_id');

            $data = array(
                'success' => true,
                'message' => '',
                'task' => array(),
            );

            try {
                $task = Mage::getModel('teamandtaskorganizer/task')->load($taskId);

                if ($task->isEmpty()) {
                    throw new Exception($this->__('No such task.'));
                }

                $permissionOtherTasks = $user->isAllowed('SEE_OTHER_TASK');
                // not owner AND not able to see all tasks AND not in allowed array
                if ($task->getUserId() != $user->getUserId() && $permissionOtherTasks != -1 && (!is_array($permissionOtherTasks) || !in_array($user->getUserId(), $permissionOtherTasks)) ) {
                    throw new Exception($this->__('You cannot view this task\'s comments.'));
                }

                $data['task']   = $task->getDataForUser($user);

                $commentPreviewBlock = $this->getLayout()->createBlock('teamandtaskorganizer/adminhtml_tasks_comments_preview');
                $commentPreviewBlock->task = $task;

                $data['html'] = $commentPreviewBlock->toHtml();	
            } catch (Exception $e){
                $data['success'] = false;
                $data['message'] = $e->getMessage();
            }

            return $this->toAjaxResponse($data);
	}
	
}
