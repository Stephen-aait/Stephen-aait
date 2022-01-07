<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-06-17, 15:32:31)
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

$installer = $this;
$installer->startSetup();

$resource = Mage::getSingleton('core/resource');
$connection = $resource->getConnection('core_read');
$configTable = $resource->getTableName('core_config_data');
$emailTemplateSender = $connection->fetchOne('SELECT value FROM '.$configTable.' WHERE path = "trans_email/ident_general/name"');
$emailTemplateSenderEmail = $connection->fetchOne('SELECT value FROM '.$configTable.' WHERE path = "trans_email/ident_general/email"');
$now = date('Y-m-d H:i:s');

if (!$emailTemplateSender)
	$emailTemplateSender = 'Store Owner';
if (!$emailTemplateSenderEmail)
	$emailTemplateSenderEmail = 'null@modulesgarden.com';

$installer->run("

CREATE TABLE IF NOT EXISTS `".$resource->getTableName('teamandtaskorganizer/task')."` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `crdate` datetime NOT NULL,
  `update` datetime DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `priority` smallint(6) unsigned DEFAULT NULL,
  `type` smallint(6) unsigned DEFAULT NULL COMMENT '1 - created automaticly after event,2 - created manually by user',
  `status` int(11) unsigned DEFAULT NULL,
  `progress` smallint(5) unsigned NOT NULL DEFAULT '0',
  `enddate` datetime DEFAULT NULL,
  `startdate` datetime DEFAULT NULL,
  `creator_id` int(10) unsigned DEFAULT NULL,
  `foreign_id` int(11) unsigned DEFAULT NULL,
  `foreign_type` varchar(255) DEFAULT NULL,
  `notify` smallint(5) unsigned DEFAULT NULL COMMENT '1 - notification enabled,0 - notofication disabled',
  `read_by_owner` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `".$resource->getTableName('teamandtaskorganizer/task_auto')."` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event` varchar(100) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `title` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `priority` smallint(5) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '0 - disabled, 1 - active',
  `notify` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `conditions_serialized` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `".$resource->getTableName('teamandtaskorganizer/task_comment')."` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int(10) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `crdate` datetime NOT NULL,
  `content` text NOT NULL,
  `read_by_task_owner` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_".$resource->getTableName('teamandtaskorganizer/task_comment')."` (`task_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `".$resource->getTableName('teamandtaskorganizer/user_privilege')."` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `settingkey` varchar(100) NOT NULL,
  `value` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`settingkey`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `".$resource->getTableName('teamandtaskorganizer/user_setting')."` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `settingkey` varchar(100) NOT NULL,
  `value` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`settingkey`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

");

// email templates
try {
	$installer->run("
		INSERT INTO `".$resource->getTableName('core_email_template')."` (`template_id`, `template_code`, `template_text`, `template_styles`, `template_type`, `template_subject`, `template_sender_name`, `template_sender_email`, `added_at`, `modified_at`, `orig_template_code`, `orig_template_variables`) VALUES
		(NULL, 'TTO New Task Assigned To You', '<p>Hello {{var task_user}},</p>\r\n\r\n<p>New task has been added for you <i>\"{{var task_title}}\"</i></p>\r\n<p><i>{{var task_description}}</i></p>\r\n<table cellspacing=\"10\">\r\n<tr>\r\n<td><strong>Status</strong></td><td>{{var task_status}}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Priority</strong></td><td>{{var task_priority}}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Start Date</strong></td><td>{{var task_startdate}}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Deadline</strong></td><td>{{var task_deadline}}</td>\r\n</tr>\r\n</table>\r\n<br/>\r\n\r\n{{config path=\"general/store_information/name\"}}<br/>\r\n{{config path=\"general/store_information/address\"}}', NULL, 2, 'New task has been added for you', '{$emailTemplateSender}', '{$emailTemplateSenderEmail}', '{$now}', '{$now}', NULL, '{\"var task_user\":\"Task User\",\"var task_title\":\"Task Title\",\"var task_description\":\"Task Description\",\"var task_priority\":\"Task Priority\",\"var task_status\":\"Task Status\",\"var task_startdate\":\"Task Start Date\",\"var task_deadline\":\"Task Deadline\",\"var task_crdate\":\"Task Creation Date\"}'),
		(NULL, 'TTO New Comment In Your Task', '<p>Hello {{var task_user}},</p>\r\n\r\n<p>New comment has been added to your task <i>\"{{var task_title}}\"</i></p>\r\n<p><i>{{var task_description}}</i></p>\r\n<p><strong>{{var comment_user}}</strong> at {{var comment_crdate}}<br/><strong>{{var comment_content}}</strong></p>\r\n\r\n<table cellspacing=\"10\">\r\n<tr>\r\n<td><strong>Status</strong></td><td>{{var task_status}}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Priority</strong></td><td>{{var task_priority}}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Start Date</strong></td><td>{{var task_startdate}}</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Deadline</strong></td><td>{{var task_deadline}}</td>\r\n</tr>\r\n</table>\r\n<br/>\r\n\r\n{{config path=\"general/store_information/name\"}}<br/>\r\n{{config path=\"general/store_information/address\"}}', NULL, 2, 'New comment has been added to your task', '{$emailTemplateSender}', '{$emailTemplateSenderEmail}', '{$now}', '{$now}', NULL, '{\"var task_user\":\"Task User\",\"var task_title\":\"Task Title\",\"var task_description\":\"Task Description\",\"var task_priority\":\"Task Priority\",\"var task_status\":\"Task Status\",\"var task_startdate\":\"Task Start Date\",\"var task_deadline\":\"Task Deadline\",\"var task_crdate\":\"Task Creation Date\",\"comment_user\":\"Comment User\",\"comment_crdate\":\"Comment Creation Date\",\"comment_content\":\"Comment Content\"}');

		INSERT INTO `".$resource->getTableName('adminnotification_inbox')."` (`notification_id`, `severity`, `date_added`, `title`, `description`, `url`, `is_read`, `is_remove`) VALUES
		(NULL, 4, NOW(), 'Team & Task Organizer has been installed', 'You will be able to manage your team', 'http://www.docs.modulesgarden.com/Team_And_Task_Organizer_For_Magento', 0, 0);
	");
} catch (Exception $e){
	
}

// foreign key
try {
	$installer->run("
		ALTER TABLE `".$resource->getTableName('teamandtaskorganizer/task_comment')."`
			ADD CONSTRAINT `".$resource->getTableName('teamandtaskorganizer/task_comment')."_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `".$resource->getTableName('teamandtaskorganizer/task')."` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
	");
} catch (Exception $e){
	
}

// if COMPILER is enabled -> manually include model file
if (defined('COMPILER_INCLUDE_PATH') || defined('COMPILER_COLLECT_PATH')){
	$modulePath = Mage::getModuleDir('', 'Modulesgarden_Teamandtaskorganizer');
	$privilegeModelFilePath		= $modulePath . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR . 'User' . DIRECTORY_SEPARATOR . 'Privilege.php';
	$privilegeResourceFilePath	= $modulePath . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR . 'Mysql4' . DIRECTORY_SEPARATOR . 'User' . DIRECTORY_SEPARATOR . 'Privilege.php';
	$moduleHelperFilePath		= $modulePath . DIRECTORY_SEPARATOR . 'Helper' . DIRECTORY_SEPARATOR . 'Data.php';
	
	if (file_exists($privilegeModelFilePath) && file_exists($moduleHelperFilePath) && file_exists($privilegeResourceFilePath)){
		require_once $privilegeResourceFilePath;
		require_once $privilegeModelFilePath;
		require_once $moduleHelperFilePath;
	} else {
		return;
	}
}

// setup full permissions for admins with full permissions in magento

$privilegeModel = new Modulesgarden_Teamandtaskorganizer_Model_User_Privilege();
$permissionsMap = $privilegeModel->getPermissionsMap();

$adminsCollection = Mage::getModel('admin/user')->getCollection()->distinct(TRUE);

$adminsCollection->join(
	array('role' => 'admin/role'),
	'main_table.user_id = role.user_id',
	array()
);
$adminsCollection->join(
	array('role2' => 'admin/role'),
	'role.parent_id = role2.role_id',
	array()
);
$adminsCollection->join(
	array('rule' => 'admin/rule'),
	'role2.role_id = rule.role_id AND rule.resource_id = "all" AND rule.permission = "allow"',
	array()
);

$permissions = array();

foreach ($permissionsMap as $group){
	foreach ($group as $permissionId => $permissionName){
		if ($permissionId == 'name')
			continue;

		$value = $permissionId === 'SEE_OTHER_TASK' || $permissionId === 'STATISTICS' ? -1 : 1;
		$permissions[$permissionId] = $value;
	}
}

foreach ($adminsCollection as $admin){
	foreach ($permissions as $permId => $permValue){
		$privilegeModel = new Modulesgarden_Teamandtaskorganizer_Model_User_Privilege();
		$privilegeModel
			->setUserId($admin->getUserId())
			->setSettingkey($permId)
			->setValue($permValue)
			->save();
	}
}

$installer->endSetup();
