<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-04-17, 11:17:22)
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

class Modulesgarden_Teamandtaskorganizer_Model_Mysql4_User_Privilege extends Mage_Core_Model_Mysql4_Abstract {
	
	protected function _construct(){
		$this->_init('teamandtaskorganizer/user_privilege', 'id');
	}
	
	public function deleteByUserId($user_id){
		$where = 'user_id = ' . (int)$user_id;
		// if current user edits his own privileges -> do not change ASSIGN_PRIVILEGES
//		$user = Mage::getSingleton('teamandtaskorganizer/user');
//		if ($user->getUserId() == $user_id){
//			$where .= ' AND `settingkey` != "ASSIGN_PRIVILEGES"';
//		}
		$resource = Mage::getSingleton('core/resource');
		$connection = $resource->getConnection('core_write');
		$connection->query('DELETE FROM '.$resource->getTableName('teamandtaskorganizer/user_privilege').' WHERE ' . $where);
	}
	
}
