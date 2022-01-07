<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-05-19, 14:09:48)
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
class Modulesgarden_Teamandtaskorganizer_Model_Mysql4_User extends Mage_Core_Model_Mysql4_Abstract {
	
	protected function _construct(){
		$this->_init('teamandtaskorganizer/user', 'id');
	}
	
	public function getStatsArray($user_id){
		$resource = Mage::getSingleton('core/resource');
		$connection = $resource->getConnection('core_read');
		$stats = array('total' => 0);
		
		$sqlStatuses = '
			SELECT status, COUNT(*) AS count
			FROM '.$resource->getTableName('teamandtaskorganizer/task').'
			WHERE user_id = '.(int)$user_id.'
			GROUP BY status
			ORDER BY status ASC';
		$sqlPriorities = '
			SELECT priority, COUNT(*) AS count
			FROM '.$resource->getTableName('teamandtaskorganizer/task').'
			WHERE user_id = '.(int)$user_id.'
			GROUP BY priority
			ORDER BY priority ASC';
		
		foreach ($connection->fetchAll($sqlStatuses) as $status){
			$stats['total'] += $status['count'];
			$stats['statuses'][$status['status']] = $status['count'];
		}
		foreach ($connection->fetchAll($sqlPriorities) as $priority){
			$stats['priorities'][$priority['priority']] = $priority['count'];
		}
		
		return $stats;
	}
	
}
