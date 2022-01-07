<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-04-15, 16:10:45)
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

class Modulesgarden_Teamandtaskorganizer_Model_Mysql4_Task extends Mage_Core_Model_Mysql4_Abstract {
	
	protected function _construct(){
		$this->_init('teamandtaskorganizer/task', 'id');
	}
	
	public static function getCommentsStatsArray($user_id){
		$resource = Mage::getSingleton('core/resource');
		$sql = '
			SELECT c.task_id, SUM(c.read_by_task_owner) AS count_read, COUNT(*) AS count_comments
			FROM '.$resource->getTableName('teamandtaskorganizer/task_comment').' AS c
			JOIN '.$resource->getTableName('teamandtaskorganizer/task').' AS t ON c.task_id = t.id
			WHERE t.user_id = '.(int)$user_id.'
			GROUP BY c.task_id';
		$connection = $resource->getConnection('core_read');
		$stats = array();
		foreach ($connection->fetchAll($sql) as $arr){
			$stats[$arr['task_id']] = array(
				'total'		=> (int)$arr['count_comments'],
				'unread'	=> $arr['count_comments'] - $arr['count_read'],
			);
		}
		return $stats;
	}
	
}
