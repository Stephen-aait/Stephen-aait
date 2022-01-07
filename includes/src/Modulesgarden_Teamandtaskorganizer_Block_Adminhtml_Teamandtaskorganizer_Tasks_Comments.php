<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-04-16, 15:11:45)
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

class Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_Teamandtaskorganizer_Tasks_Comments extends Mage_Adminhtml_Block_Widget_Grid_Container {

	public function __construct() {
		$this->_blockGroup = 'teamandtaskorganizer';
		$this->_controller = 'adminhtml_teamandtaskorganizer_tasks_comments';
		$this->_headerText = Mage::helper('teamandtaskorganizer')->__('Task Comments');
		parent::__construct();
		
		// modulesgarden template
		$this->setTemplate('modulesgardenbase/widget/grid/container.phtml');
		$this->setSkipHeaderCopy(true);
	}

	protected function _prepareLayout() {
		parent::_prepareLayout();
		
		$user = Mage::getSingleton('teamandtaskorganizer/user');
		$task = Mage::getModel('teamandtaskorganizer/task')
			->load(Mage::app()->getRequest()->getParam('id'));
		
		if (!$user->isAllowedToAddComment($task)){
			$this->_removeButton('add');
		}
		
		$this->getChild('grid')->setTask($task);
	}
	
	public function getCreateUrl(){
		return $this->getUrl('*/*/editcomment', array('task_id' => Mage::app()->getRequest()->getParam('id') ));
	}

	// @todo better way to resolve it
	protected function _toHtml() {
		return '<div class="main-col-inner">' . parent::_toHtml() . '</div>';
	}

}