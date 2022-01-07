<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-05-07, 12:25:32)
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

class Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_Managesuperadmin_Users_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {
	
	public function __construct(){
		parent::__construct();
		$this->_blockGroup = 'teamandtaskorganizer';
		$this->_controller = 'adminhtml_managesuperadmin_users';
		$this->_headerText = Mage::helper('teamandtaskorganizer')->__('Edit User\'s Privileges');
		
		$this->_removeButton('reset');
		$this->_removeButton('delete');
		
		// modulesgarden template
		$this->setTemplate('modulesgardenbase/widget/form/container.phtml');
		$this->setSkipHeaderCopy(true);
	}
	
	public function getBackUrl(){
		return $this->getUrl('*/*/', array(
			'tab' => 'teamandtaskorganizer_managesuperadmin_tabs_user_privileges'
		));
	}
}
