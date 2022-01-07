<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-04-16, 10:31:22)
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

class Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_Tasks_Edit_Form extends Mage_Adminhtml_Block_Widget_Form {

	protected $_task_id;
	
	protected function _construct(){
		$this->_task_id = Mage::app()->getRequest()->getParam('id');
		parent::_construct();
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

	protected function _prepareForm() {
		$form = new Varien_Data_Form();
		$form->setUseContainer(true);
		$form->setId('edit_form');
		$this->setForm($form);
		
		$user = Mage::getSingleton('teamandtaskorganizer/user');
		if ($user->isAllowed('ASSIGN_TASK')){
			$usersOptions = $this->_prepareAdminsList();
		} else {
			$usersOptions = array( $user->getUserId() => $user->getParentObject()->getLastname() . ' ' . $user->getParentObject()->getFirstname() );
		}
		
		$fieldset = $form->addFieldset('teamandtaskorganizer_form', array('legend' => Mage::helper('teamandtaskorganizer')->__('Task')));

		$fieldset->addField('user_id', 'select', array(
			'name' => 'user_id',
			'label' => Mage::helper('teamandtaskorganizer')->__('Admin'),
			'options' => $usersOptions
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
		$fieldset->addField('status', 'select', array(
			'name' => 'status',
			'label' => Mage::helper('teamandtaskorganizer')->__('Status'),
			'options' => Modulesgarden_Teamandtaskorganizer_Model_Task::getStatusMap()
		));
		$fieldset->addField('progress', 'text', array(
			'name' => 'progress',
			'label' => Mage::helper('teamandtaskorganizer')->__('Progress'),
			'note' => '0 - 100 %',
                        'style' => 'width:30px !important',
                        'container_id' => 'tto-progress-row',
		));
		$fieldset->addField('notify', 'checkbox', array(
			'name' => 'notify',
			'label' => Mage::helper('teamandtaskorganizer')->__('Send Notification'),
		));
		$fieldset->addField('startdate', 'text', array(
			'name'  => 'startdate',
			'label' => Mage::helper('teamandtaskorganizer')->__('Start Date'),
			'image' => Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN) . '/adminhtml/default/default/images/grid-cal.gif',
			'style' => 'width:100px !important',
                        'container_id' => 'tto-startdate-row'
		));
		$fieldset->addField('enddate', 'text', array(
			'name'  => 'enddate',
			'label' => Mage::helper('teamandtaskorganizer')->__('Deadline'),
			'image' => Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN) . '/adminhtml/default/default/images/grid-cal.gif',
			'style' => 'width:100px !important',
                        'container_id' => 'tto-enddate-row'
		));
		
		if ($this->_task_id) {
			$fieldset->addField('task_id', 'hidden', array(
				'name' => 'task_id',
				'value' => $this->_task_id
			));
			
			$task = Mage::getModel('teamandtaskorganizer/task')
				->load($this->_task_id);
			$form->setValues($task->getData());
			$fieldset->getForm()->getElement('task_id')->setvalue($this->_task_id);
			$fieldset->getForm()->getElement('notify')->setChecked( $task->getNotify() );
                        $fieldset->getForm()->getElement('startdate')->setvalue( $task->getStartdate() );
			$fieldset->getForm()->getElement('enddate')->setvalue( $task->getEnddate() );
		}
		
		if (Mage::app()->getRequest()->isAjax()){
                        $jsEvent    = $this->_task_id ? 'updated' : 'created';
			$adding     = Mage::registry('tto_ajax_adding_new_task');
                        
			$fieldset->addField('submit', 'note', array(
				'name' => 'submit',
				'text' => '
					<button type="button" class="scalable ok" id="edit_task_ajax_form_submit" onclick="">
						<span><span><span>'.($adding ? 'Add Task' : 'Edit Task').'</span></span></span>
					</button>
					<script type="text/javascript">
						(function($){
							$(document).ready(function(){
								$("#edit_task_ajax_form_submit").click(function(){
									var
                                                                            values = {},
                                                                            $this = $(this),
                                                                            $form = $this.closest("form"),
                                                                            form = new varienForm( $form.attr("id"), "");
                                                                        
                                                                        if( ! (form.validator &&  form.validator.validate()) ) {
                                                                            return false;
                                                                        }
                                                                        
                                                                        $this.prop("disabled", true);
                                                                            
									$form.find(":input").each(function(){
										values[$(this).prop("name")] = $(this).val();
									});
									$.post("'.Mage::helper('adminhtml')->getUrl("teamandtaskorganizer/adminhtml_task/save").'", values, function(response){
                                                                                tto.popup.close();
                                                                                
                                                                                $(document)
                                                                                    .trigger("tto/task/' . $jsEvent . '", [response.task, response.message])
                                                                                    .trigger("tto/task/saved", [response.task, response.message]);
									});
									return false;
								});
							});
						})(jQuery);
					</script>',
			));
			$form->setId('edit_task_ajax_form');
		}
		
		if (Mage::registry('tto_back_to_managesuperadmin')){
			$fieldset->addField('back', 'hidden', array(
				'name' => 'back',
				'value' => "teamandtaskorganizer/adminhtml_managesuperadmin/index"
			));
		}

		$form->setAction($this->getUrl('*/*/save'))
			->setMethod('post');
		
		$this->setForm($form);
		return parent::_prepareForm();
	}

}