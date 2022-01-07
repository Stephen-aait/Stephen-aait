<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-05-29, 11:38:57)
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

class Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_Widget extends Mage_Adminhtml_Block_Template {
	
	protected function _toHtml() {
		if (!Mage::getSingleton('admin/session')->isAllowed('teamandtaskorganizer/teamandtaskorganizer_task'))
			return '';
		
		try {
			include_once Mage::getModuleDir('', 'Modulesgarden_Teamandtaskorganizer') . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR . 'lic.php';
			$license_check = team_and_task_organizer_for_magento_license_3296();
if($license_check['status'] != 'Active'){
    $error_message=isset($license_custom_error_message)?$license_custom_error_message:("License {$license_check['status']}".($license_check['description'] ? ": {$license_check['description']}" : ""));
	throw new Exception($error_message);
}
			
			return parent::_toHtml();
			
		} catch (Exception $e){
			
		}
		
		return '';
	}

}
