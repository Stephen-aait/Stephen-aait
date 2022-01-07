<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-04-17, 11:08:29)
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

class Modulesgarden_Teamandtaskorganizer_Model_User_Privilege extends Mage_Core_Model_Abstract {
	
	protected function _construct(){
		$this->_init('teamandtaskorganizer/user_privilege');
	}
	
	public function getPermissionsMap(){
		$h = Mage::helper('teamandtaskorganizer');
		return array(
			'main' => array(
				'name'								=> $h->__('Main Permissions'),
				'ADD_TASK'							=> $h->__('Adding Tasks'),
				'ASSIGN_TASK'						=> $h->__('Assign Tasks'),
				'ADD_AUTOTASK'						=> $h->__('Adding Auto Tasks'),
				'ASSIGN_PRIVILEGES'					=> $h->__('Assign Privileges'),
				'STATISTICS'						=> $h->__('See Statistics'),
			),
			'own_tasks' => array(
				'name'								=> $h->__('Own Tasks'),
				'EDIT_OWN_TASK'						=> $h->__('Edit Own Tasks'),
				'DELETE_OWN_TASK'					=> $h->__('Delete Own Tasks'),
				'ADD_COMMENT_OWN_TASK'				=> $h->__('Comment Own Tasks'),
				'EDIT_OWN_COMMENT_IN_OWN_TASK'		=> $h->__('Edit Own Comments'),
				'DELETE_OWN_COMMENT_IN_OWN_TASK'	=> $h->__('Delete Own Comments'),
				'EDIT_OTHER_COMMENT_IN_OWN_TASK'	=> $h->__('Edit Other Comments'),
				'DELETE_OTHER_COMMENT_IN_OWN_TASK'	=> $h->__('Delete Other Comments'),
			),
			'other_tasks' => array(
				'name'								=> $h->__('Other Tasks'),
				'SEE_OTHER_TASK'					=> $h->__('See Other Tasks'),
				'EDIT_OTHER_TASK'					=> $h->__('Edit Other Tasks'),
				'DELETE_OTHER_TASK'					=> $h->__('Delete Other Tasks'),
				'ADD_COMMENT_OTHER_TASK'			=> $h->__('Comment Other Tasks'),
				'EDIT_OWN_COMMENT_IN_OTHER_TASK'	=> $h->__('Edit Own Comments'),
				'EDIT_OTHER_COMMENT_IN_OTHER_TASK'	=> $h->__('Edit Other Comments'),
				'DELETE_OWN_COMMENT_IN_OTHER_TASK'	=> $h->__('Delete Own Comments'),
				'DELETE_OTHER_COMMENT_IN_OTHER_TASK'=> $h->__('Delete Other Comments'),
			),
		);
	}
	
}
