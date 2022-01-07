<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-04-18, 13:40:15)
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

class Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_Tasks_Comments_Edit_Form extends Mage_Adminhtml_Block_Widget_Form {

	/**
	 * required $_GET['task_id']
	 * @return Varien_Data_Form
	 */
	protected function _prepareForm() {
		$form = new Varien_Data_Form();
		$form->setUseContainer(true);
		$form->setId('edit_form');
		$this->setForm($form);
		$comment	= Mage::getSingleton('teamandtaskorganizer/task_comment');
		$fieldset	= $form->addFieldset('teamandtaskorganizer_comment_form', array('legend' => Mage::helper('teamandtaskorganizer')->__('Comment Form')));

		$fieldset->addField('content', 'editor', array(
			'name' => 'content',
			'label' => Mage::helper('teamandtaskorganizer')->__('Comment Content'),
			'required' => true
		));
		$fieldset->addField('task_id', 'hidden', array(
			'name' => 'task_id',
			'value' => Mage::app()->getRequest()->getParam('task_id')
		));
		
		if (!$comment->isEmpty()){
			$fieldset->addField('comment_id', 'hidden', array(
				'name' => 'comment_id',
				'value' => $comment->getId()
			));
			$comment->setContent(str_replace('<br />', "", $comment->getContent()));
			
			$form->setValues($comment->getData());
			$fieldset->getForm()->getElement('comment_id')->setvalue($comment->getId());
		}

		$form->setAction($this->getUrl('*/adminhtml_task/saveComment'))
			->setMethod('post');

		$this->setForm($form);
		return parent::_prepareForm();
	}

}