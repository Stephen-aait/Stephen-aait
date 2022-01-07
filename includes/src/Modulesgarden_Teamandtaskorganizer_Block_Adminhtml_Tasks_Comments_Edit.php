<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-04-17, 09:41:51)
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

class Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_Tasks_Comments_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

	public function __construct(){
		parent::__construct();
		$this->_objectId = 'id';
		$this->_blockGroup = 'teamandtaskorganizer';
		$this->_controller = 'adminhtml_tasks_comments';
		$this->_headerText = Mage::helper('teamandtaskorganizer')->__('Edit Comment');
		
		$this->_removeButton('reset');
		
		// modulesgarden template
		$this->setTemplate('modulesgardenbase/widget/form/container.phtml');
		$this->setSkipHeaderCopy(true);
	}

	protected function _prepareLayout() {
		$user	= Mage::getSingleton('teamandtaskorganizer/user');
		$comment= Mage::getSingleton('teamandtaskorganizer/task_comment');
		
		$this->_updateButton('save', 'label', Mage::helper('teamandtaskorganizer')->__('Save Comment'));
		$this->_updateButton('back', 'onclick', "setLocation('" . $this->getUrl('*/*/edittask', array('id' => $comment->getTask()->getId(), 'tab' => 'teamandtaskorganizer_tasks_tabs_task_comments')) . "')");
		$this->_updateButton('delete', 'label', Mage::helper('teamandtaskorganizer')->__('Delete Comment'));
		$this->_updateButton('delete', 'onclick', "deleteConfirm('" . Mage::helper('teamandtaskorganizer')->__('Are you sure you want to do this?') . "', '" . $this->getUrl('*/*/deleteComment', array('id' => $comment->getId())) . "')");
		
		if ($comment->isEmpty() || !$user->isAllowedToDelete($comment)){
			$this->removeButton('delete');
		}
		return parent::_prepareLayout();
	}

}
