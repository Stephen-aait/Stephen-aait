<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-06-17, 11:16:08)
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
class Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_Support_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {
	
	public function __construct(){
		parent::__construct();
		
		$this->_objectId = 'ttorganizer_task_id';
		$this->_blockGroup = 'teamandtaskorganizer';
		$this->_controller = 'adminhtml_support';
		$this->_headerText = Mage::helper('teamandtaskorganizer')->__('Open Support Ticket');
		
		$this->_removeButton('reset');
		$this->_updateButton('save', 'label', Mage::helper('teamandtaskorganizer')->__('Send Ticket'));
		
		// modulesgarden template
		$this->setTemplate('modulesgardenbase/widget/form/container.phtml');
		$this->setSkipHeaderCopy(true);
	}
	
	// @todo better way to resolve it
	protected function _toHtml(){
		return '<div class="main-col-inner">'.parent::_toHtml().'</div>';
	}
	
}
