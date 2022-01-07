<?php

/**********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-11-12, 10:35:44)
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
 **********************************************************************/

/**
 * @author Grzegorz Draganik <grzegorz@modulesgarden.com>
 */

class Modulesgarden_Base_Model_Adminnotification_Inbox extends Mage_AdminNotification_Model_Inbox {
	
	public static function exists($title){
		$resource = Mage::getSingleton('core/resource');
		$sql = "SELECT 1 FROM ".$resource->getTableName('adminnotification_inbox')." WHERE title = :title";
		return (bool)$resource->getConnection('core_read')->fetchOne($sql, array(
			'title' => $title
		));
	}
	
}
