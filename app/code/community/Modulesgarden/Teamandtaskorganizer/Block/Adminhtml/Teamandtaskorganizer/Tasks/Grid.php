<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-04-14, 16:24:05)
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
class Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_Teamandtaskorganizer_Tasks_Grid
        extends Mage_Adminhtml_Block_Widget_Grid
{

    protected $_tasksFrom;
    protected $_user;
    protected $_statsUser;

    public function __construct()
    {
        $this->_statsUser = Mage::registry('tto_stats_user');
        parent::__construct();

        $this->setId('teamandtaskorganizerTasksGrid');
        $this->setDefaultSort('priority');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(false);

        $this->_user = Mage::getModel('teamandtaskorganizer/user');

        $this->_tasksFrom = $this->_user->isAllowed('SEE_OTHER_TASK');
        if (Mage::registry('tto_grid_onlycurrentuser') === true || is_null($this->_tasksFrom)) {
            $this->_tasksFrom = array($this->_user->getUserId());
        }

        if ($this->_statsUser)
            $this->setFilterVisibility(false);
    }

    protected function _getCollectionClass()
    {
        return 'teamandtaskorganizer/task_collection';
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel($this->_getCollectionClass());
        if ($this->_tasksFrom == -1) {
            
        } elseif ($this->_tasksFrom !== false) {
            $collection->addFieldToFilter('user_id', array('in' => $this->_tasksFrom));
        } else {
            $collection->addFieldToFilter('user_id', $this->_user->getUserId());
        }

        if ($this->_statsUser) {
            $collection->addFieldToFilter('status', Modulesgarden_Teamandtaskorganizer_Model_Task::STATUS_PROGRESS);
            $collection->addFieldToFilter('user_id', $this->_statsUser->getUserId());
        }

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {

        if ($this->_tasksFrom == -1)
            $users = Modulesgarden_Teamandtaskorganizer_Model_User::getList(false);
        elseif ($this->_tasksFrom !== false)
            $users = Modulesgarden_Teamandtaskorganizer_Model_User::getList(false, true, $this->_tasksFrom);
        else
            $users = Modulesgarden_Teamandtaskorganizer_Model_User::getList(true);

        $this->addColumn('id', array(
            'header' => Mage::helper('teamandtaskorganizer')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'id',
            'renderer' => 'Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_Teamandtaskorganizer_Template_Grid_Renderer_Unreaded',
        ));

        $this->addColumn('title', array(
            'header' => Mage::helper('teamandtaskorganizer')->__('Title'),
            'align' => 'left',
            'index' => 'title',
            'column_css_class' => 'task-title',
            'renderer' => 'Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_Teamandtaskorganizer_Template_Grid_Renderer_Unreaded',
        ));

        $this->addColumn('crdate', array(
            'header' => Mage::helper('teamandtaskorganizer')->__('Date'),
            'align' => 'left',
            'index' => 'crdate',
            'type' => 'datetime',
            'align' => 'center',
            'gmtoffset' => true,
            'width' => '120px',
        ));

        $this->addColumn('foreignitem', array(
            'header' => Mage::helper('teamandtaskorganizer')->__('Item'),
            'align' => 'left',
            'width' => '120px',
            'renderer' => 'Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_Teamandtaskorganizer_Template_Grid_Renderer_Foreigntaskitem',
            'filter' => false,
        ));

        if (!Mage::registry('tto_grid_onlycurrentuser')) {
            $this->addColumn('user_id', array(
                'header' => Mage::helper('teamandtaskorganizer')->__('User'),
                'align' => 'left',
                'width' => '180px',
                'index' => 'user_id',
                'type' => 'options',
                'column_css_class' => 'task-user',
                'options' => $users,
                'sortable' => false
            ));
        }

        $this->addColumn('status', array(
            'header' => Mage::helper('teamandtaskorganizer')->__('Status'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'status',
            'column_css_class' => 'task-status',
            'type' => 'options',
            'options' => Modulesgarden_Teamandtaskorganizer_Model_Task::getStatusMap(),
            'sortable' => false
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
        $this->addColumn('progress', array(
            'header' => Mage::helper('teamandtaskorganizer')->__('Progress'),
            'align' => 'left',
            'width' => '150px',
            'index' => 'progress',
            'renderer' => 'Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_Teamandtaskorganizer_Template_Grid_Renderer_Progress',
            'filter' => false,
        ));

        $this->addColumn('action', array(
            'header' => Mage::helper('teamandtaskorganizer')->__('Action'),
            'width' => '170',
            'type' => 'action',
            'renderer' => 'Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_Teamandtaskorganizer_Template_Grid_Renderer_Action',
            'getter' => 'getId',
            'gridType' => 'task_list',
            'actions' => array(
                'view' => array(
                    'caption' => Mage::helper('teamandtaskorganizer')->__('View'),
                    'url' => array('base' => '*/teamandtaskorganizer_tasks/viewtask'),
                    'class' => 'ajax-link',
                    'field' => 'id'
                ),
                'edit' => array(
                    'caption' => Mage::helper('teamandtaskorganizer')->__('Edit'),
                    'url' => array('base' => '*/teamandtaskorganizer_tasks/edittask/parent/taskslist'),
                    'class' => 'ajax-link',
                    'field' => 'id'
                ),
                'delete' => array(
                    'caption' => Mage::helper('teamandtaskorganizer')->__('Delete'),
                    'url' => array(
                        'base' => '*/teamandtaskorganizer_tasks/delete',
                        'params' => array('backUrl' => 'referer')
                    ),
                    'confirm' => Mage::helper('teamandtaskorganizer')->__('Are you sure you want to do this?'),
                    'field' => 'id'
                )
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'action',
            'is_system' => true,
        ));

//		$this->addExportType('*/*/exportCsv', Mage::helper('teamandtaskorganizer')->__('CSV'));
//		$this->addExportType('*/*/exportXml', Mage::helper('teamandtaskorganizer')->__('XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
//		$this->setMassactionIdField('id');
//		$this->getMassactionBlock()->setFormFieldName('teamandtaskorganizer');
//
//		$this->getMassactionBlock()->addItem('delete', array(
//			'label' => Mage::helper('teamandtaskorganizer')->__('Delete'),
//			'url' => $this->getUrl('*/*/massDelete'),
//			'confirm' => Mage::helper('teamandtaskorganizer')->__('Are you sure?')
//		));
//
//		$this->getMassactionBlock()->addItem('status', array(
//			'label' => Mage::helper('teamandtaskorganizer')->__('Change status'),
//			'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
//			'additional' => array(
//				'visibility' => array(
//					'name' => 'status',
//					'type' => 'select',
//					'class' => 'required-entry',
//					'label' => Mage::helper('teamandtaskorganizer')->__('Status'),
//					'values' => Modulesgarden_Teamandtaskorganizer_Model_Task::getStatusMap()
//				)
//			)
//		));
//		$this->getMassactionBlock()->addItem('priority', array(
//			'label' => Mage::helper('teamandtaskorganizer')->__('Change priority'),
//			'url' => $this->getUrl('*/*/massPriority', array('_current' => true)),
//			'additional' => array(
//				'visibility' => array(
//					'name' => 'priority',
//					'type' => 'select',
//					'class' => 'required-entry',
//					'label' => Mage::helper('teamandtaskorganizer')->__('Priority'),
//					'values' => Modulesgarden_Teamandtaskorganizer_Model_Task::getPriorityMap()
//				)
//			)
//		));
//		return $this;
    }

    public function getRowUrl($row)
    {
        if (!$this->_user->isAllowedToEdit($row)) {
            return false;
        }
        return $this->getUrl('*/teamandtaskorganizer_tasks/edittask', array('id' => $row->getId()));
    }

}
