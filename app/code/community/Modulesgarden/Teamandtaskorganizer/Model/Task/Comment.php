<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-04-16, 14:36:39)
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

class Modulesgarden_Teamandtaskorganizer_Model_Task_Comment extends Mage_Core_Model_Abstract {
	
	protected function _construct(){
		$this->_init('teamandtaskorganizer/task_comment');
	}
	
	public function getTask(){
		return Mage::getModel('teamandtaskorganizer/task')
			->load($this->getTaskId());
	}
	
	public function sendNotificationToOwner(){
		$task = $this->getTask();
		$emailTemplate = Mage::getModel('core/email_template')
			->loadByCode(Modulesgarden_Teamandtaskorganizer_Model_Task::EMAIL_TO_OWNER_AFTER_COMMENT);

		// magento bug
		$emailTemplate->setSenderName( $emailTemplate->getTemplateSenderName() );
		$emailTemplate->setSenderEmail( $emailTemplate->getTemplateSenderEmail() );

		// set details from main config instead of template
		$conf = Mage::getStoreConfig('trans_email/ident_general');
		if (isset($conf['name']) && isset($conf['email'])){
			$emailTemplate->setSenderName( $conf['name'] );
			$emailTemplate->setSenderEmail( $conf['email'] );
		}
		
		$taskUser = Mage::getModel('admin/user')->load($task->getUserId());
		$commentUser = Mage::getModel('admin/user')->load($this->getUserId());
		$vars = array(
			'task_user'			=> $taskUser->getFirstname() . ' ' . $taskUser->getLastname(),
			'task_title'		=> $task->getTitle(),
			'task_description'	=> nl2br($task->getDescription()),
			'task_priority'		=> $task->getPriorityMap($task->getPriority()),
			'task_status'		=> $task->getStatusMap($task->getStatus()),
			'task_startdate'	=> substr($task->getStartdate(), 0, 10),
			'task_deadline'		=> substr($task->getEnddate(), 0, 10),
			'task_crdate'		=> $task->getCrdate(),
			'comment_user'		=> $commentUser->getFirstname() . ' ' . $commentUser->getLastname(),
			'comment_crdate'	=> $this->getCrdate(),
			'comment_content'	=> $this->getContent(),
		);

		return $emailTemplate->send($taskUser->getEmail(), $taskUser->getLastname() . ' ' . $taskUser->getFirstname(), $vars);
	}
	
}