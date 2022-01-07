<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-05-09, 10:03:19)
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

class Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_Teamandtaskorganizer_Managesuperadmin_Autotasks_Tabs_Details extends Mage_Adminhtml_Block_Widget_Form {

	protected function _prepareForm() {
		$form = new Varien_Data_Form();
		$autoTask = Mage::getSingleton('teamandtaskorganizer/task_auto');
		$isEditing = (bool)$autoTask->getId();
		
		$usersOptions = $this->_prepareAdminsList();
		
		$fieldset = $form->addFieldset('autotaskdetails', array(
			'legend' => Mage::helper('teamandtaskorganizer')->__('Details')
		));
		
		
		if ($isEditing){
			$fieldset->addField('event_disabled', 'select', array(
				'name' => 'event_disabled',
				'label' => Mage::helper('teamandtaskorganizer')->__('Event'),
				'options' => $autoTask->getEventsArray(),
				'disabled' => true,
				'value' => $autoTask->getEvent()
			));
			$fieldset->addField('event', 'hidden', array(
				'name' => 'event',
				'value' => $autoTask->getEvent()
			));
		} else {
			$fieldset->addField('event', 'select', array(
				'name' => 'event',
				'label' => Mage::helper('teamandtaskorganizer')->__('Event'),
				'options' => $autoTask->getEventsArray(),
				'required' => true,
			));
		}
		
		$fieldset->addField('status', 'select', array(
			'name' => 'status',
			'label' => Mage::helper('teamandtaskorganizer')->__('Status'),
			'options' => array(
				Modulesgarden_Teamandtaskorganizer_Model_Task_Auto::STATUS_ENABLED => Mage::helper('teamandtaskorganizer')->__('Enabled'),
				Modulesgarden_Teamandtaskorganizer_Model_Task_Auto::STATUS_DISABLED => Mage::helper('teamandtaskorganizer')->__('Disabled'),
			),
			'required' => true,
			'value' => 1
		));
		$fieldset->addField('user_id', 'select', array(
			'name' => 'user_id',
			'label' => Mage::helper('teamandtaskorganizer')->__('Admin'),
			'options' => $usersOptions,
			'required' => true
		));
		$fieldset->addField('title', 'text', array(
			'name' => 'title',
			'label' => Mage::helper('teamandtaskorganizer')->__('Title'),
			'class' => 'required-entry',
			'required' => true,
		));
		$fieldset->addField('description', 'editor', array(
			'name' => 'description',
			'label' => Mage::helper('teamandtaskorganizer')->__('Description'),
		));
		$fieldset->addField('priority', 'select', array(
			'name' => 'priority',
			'label' => Mage::helper('teamandtaskorganizer')->__('Priority'),
			'options' => Modulesgarden_Teamandtaskorganizer_Model_Task::getPriorityMap()
		));
		$fieldset->addField('notify', 'checkbox', array(
			'name' => 'notify',
			'label' => Mage::helper('teamandtaskorganizer')->__('Send Notification'),
		));
		
		$vars = '';
		foreach ($autoTask->getEventsArray() as $eventId => $eventName){
			$vars .= '<span class="auto_task_vars vars_'.$eventId.'">';
			foreach ($autoTask->getEventVars($eventId) as $varName){
				$vars .= '{$' . $varName . '}<br/>';
			}
			$vars .= '</span>';
		}
		$fieldset->addField('note', 'note', array(
			'text' => '<small>Possible Variables in Title and Description:<br/>'.$vars.'</small>',
		));
		
		$fieldset->addField('id', 'hidden', array(
			'name' => 'id',
			'value' => $autoTask->getId()
		));
		
		if (!$autoTask->isEmpty()){
			$values = $autoTask->getData();
			$values['event_disabled'] = $autoTask->getEvent();
			$form->setValues($values);
			if ($autoTask->getNotify())
				$fieldset->getForm()->getElement('notify')->setChecked( $autoTask->getNotify() );
		}
		

		$this->setForm($form);
		return parent::_prepareForm();
	}
	
	protected function _prepareAdminsList() {
		$users = Mage::getModel('admin/user')->getCollection()
			->addFieldToFilter('is_active', 1);
		$usersArray = array();
		foreach ($users as $user) {
			$usersArray[$user->getUserId()] = $user->getLastname() . ' ' . $user->getFirstname();
		}
		return $usersArray;
	}
	
}
