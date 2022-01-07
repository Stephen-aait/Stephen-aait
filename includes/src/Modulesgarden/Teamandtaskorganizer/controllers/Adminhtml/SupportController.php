<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-06-17, 10:35:38)
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

/**
 * @s
 */
class Modulesgarden_Teamandtaskorganizer_Adminhtml_SupportController extends Modulesgarden_Teamandtaskorganizer_Controller_Action {

	protected function _initAction(){
		$this->loadLayout()->_setActiveMenu('teamandtaskorganizer/teamandtaskorganizer_task');
		return $this;
	}
	
	public function indexAction(){
		$this->_initAction();
		$this->renderLayout();
	}
	
	public function saveAction(){
		$r = $this->getRequest();
		$values = array(
			'name' => urlencode($r->getPost('name')),
			'email' => urlencode($r->getPost('email')),
			'subject' => urlencode($r->getPost('subject')),
			'message' => urlencode($r->getPost('message'))
		);
		
		$mailconf = Mage::getStoreConfig('trans_email/ident_general');
		$path = Mage::getModuleDir('', 'Modulesgarden_Teamandtaskorganizer') . DS . 'license.php';
		if (file_exists($path))
			require_once $path;
		
		$client = new Zend_Http_Client('https://www.modulesgarden.com/manage/modules/addons/modulesChecker/check.php');
		$client->setParameterPost('license', $team_and_task_organizer_licensekey);
		$client->setParameterPost('module', 'teamandtaskorganizer');
		$client->setParameterPost('version', (string)Mage::getConfig()->getModuleConfig("Modulesgarden_Teamandtaskorganizer")->version);
		$client->setParameterPost('systemurl', Mage::getStoreConfig('web/secure/base_url'));
		$client->setParameterPost('systememail', $mailconf['email']);
		$client->setParameterPost('systemowner', $mailconf['name']);
		$client->request(Zend_Http_Client::POST);
		
		$this->_redirectUrl('https://www.modulesgarden.com/customers/support?message='.$values['message'].'&name='.$values['name'].'&email='.$values['email'].'&subject='.$values['subject']);
	}
	
}