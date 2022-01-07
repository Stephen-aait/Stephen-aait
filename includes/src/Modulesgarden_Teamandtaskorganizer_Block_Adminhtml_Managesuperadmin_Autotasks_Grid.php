<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-05-08, 11:39:17)
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

class Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_managesuperadmin_Autotasks_Grid extends Mage_Adminhtml_Block_Widget_Grid {

	public function __construct() {
		parent::__construct();
		$this->setId('teamandtaskorganizerAutotasksGrid');
		$this->setDefaultSort('id');
		$this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(true);
	}

	protected function _getCollectionClass() {
		return 'teamandtaskorganizer/task_auto_collection';
	}

	protected function _prepareCollection() {
		$collection = Mage::getResourceModel($this->_getCollectionClass());
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns() {
		$this->addColumn('id', array(
			'header' => Mage::helper('teamandtaskorganizer')->__('ID'),
			'align' => 'right',
			'width' => '50px',
			'index' => 'id',
		));

		$this->addColumn('event', array(
			'header' => Mage::helper('teamandtaskorganizer')->__('Event'),
			'align' => 'left',
			'index' => 'event',
			'sortable' => false,
			'type' => 'options',
			'options' => Mage::getModel('teamandtaskorganizer/task_auto')->getEventsArray()
		));
		
		$this->addColumn('title', array(
			'header' => Mage::helper('teamandtaskorganizer')->__('Title'),
			'align' => 'left',
			'index' => 'title',
		));
		
		$this->addColumn('user_id', array(
			'header' => Mage::helper('teamandtaskorganizer')->__('User'),
			'align' => 'left',
			'index' => 'user_id',
			'sortable' => false,
			'type' => 'options',
			'width' => '180px',
			'options' => Modulesgarden_Teamandtaskorganizer_Model_User::getList(true),
		));

		$this->addColumn('status', array(
			'header' => Mage::helper('teamandtaskorganizer')->__('Status'),
			'align' => 'left',
			'width' => '80px',
			'index' => 'status',
			'type' => 'options',
			'options' => array(
				Modulesgarden_Teamandtaskorganizer_Model_Task_Auto::STATUS_DISABLED => Mage::helper('teamandtaskorganizer')->__('Disabled'),
				Modulesgarden_Teamandtaskorganizer_Model_Task_Auto::STATUS_ENABLED => Mage::helper('teamandtaskorganizer')->__('Enabled'),
			),
			'sortable' => false,
		));
		
		$this->addColumn('priority', array(
			'header' => Mage::helper('teamandtaskorganizer')->__('Priority'),
			'align' => 'left',
			'width' => '80px',
			'index' => 'priority',
			'type' => 'options',
			'column_css_class' => 'task-priority',
			'options' => Modulesgarden_Teamandtaskorganizer_Model_Task::getPriorityMap(),
		));

		$this->addColumn('action', array(
			'header' => Mage::helper('admin')->__('Action'),
			'width' => '100',
			'type' => 'action',
			'getter' => 'getId',
			'actions' => array(
				array(
					'caption' => Mage::helper('admin')->__('Edit'),
					'url' => array('base' => '*/*/editautotask'),
					'field' => 'id'
				)
			),
			'filter' => false,
			'sortable' => false,
			'index' => 'actions',
			'is_system' => true,
		));
		
		return parent::_prepareColumns();
	}

	public function getRowUrl($row) {
		return $this->getUrl('*/*/editautotask', array('id' => $row->getId()));
	}
	
	protected function _toHtml() {
		$additional = '
			<script type="text/javascript">
			jQuery(document).ready(function(){
				teamandtaskorganizerAutotasksGridJsObject.addVarToUrl("tab","teamandtaskorganizer_managesuperadmin_tabs_auto_tasks");
			});
			</script>
		';
		return $additional . parent::_toHtml();
	}

}
