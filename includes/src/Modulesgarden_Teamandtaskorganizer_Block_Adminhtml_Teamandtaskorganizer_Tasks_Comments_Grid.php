<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-04-16, 14:32:05)
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
class Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_Teamandtaskorganizer_Tasks_Comments_Grid
        extends Mage_Adminhtml_Block_Widget_Grid
{

    protected $_task_id;

    public function __construct()
    {
        $this->_task_id = Mage::app()->getRequest()->getParam('id');
        parent::__construct();
        $this->setId('teamandtaskorganizerCommentGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(false);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('teamandtaskorganizer/task_comment')->getCollection();
        if ($this->_task_id) {
            $collection->addFieldToFilter('task_id', $this->_task_id);
        }
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _afterLoadCollection()
    {
        foreach ($this->getCollection() as $comment) {
            $comment->setContent(str_replace('<br />', "\n", $comment->getContent()));
        }
        return $this;
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header' => Mage::helper('teamandtaskorganizer')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'id',
//			'sortable' => false,
        ));

        $this->addColumn('crdate', array(
            'header' => Mage::helper('teamandtaskorganizer')->__('Date'),
            'align' => 'left',
            'index' => 'crdate',
            'type' => 'date',
            'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM),
//			'sortable' => false,
        ));

        $this->addColumn('user_id', array(
            'header' => Mage::helper('teamandtaskorganizer')->__('User'),
            'align' => 'left',
            'width' => '180px',
            'index' => 'user_id',
            'type' => 'options',
            'column_css_class' => 'task-user',
            'options' => Modulesgarden_Teamandtaskorganizer_Model_User::getList(false),
            'sortable' => false
        ));

        $this->addColumn('content', array(
            'header' => Mage::helper('teamandtaskorganizer')->__('Content'),
            'align' => 'left',
            'index' => 'content',
            'sortable' => false,
        ));

        $this->addColumn('action', array(
            'header' => Mage::helper('teamandtaskorganizer')->__('Action'),
            'width' => '100',
            'type' => 'action',
            'renderer' => 'Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_Teamandtaskorganizer_Template_Grid_Renderer_Action',
            'getter' => 'getId',
            'gridType' => 'comment_list',
            'actions' => array(
                'edit' => array(
                    'caption' => Mage::helper('teamandtaskorganizer')->__('Edit'),
                    'url' => array('base' => '*/teamandtaskorganizer_tasks/editcomment'),
                    'field' => 'id'
                ),
                'delete' => array(
                    'caption' => Mage::helper('teamandtaskorganizer')->__('Delete'),
                    'url' => array(
                        'base' => '*/*/deletecomment',
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

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return false;
    }

    protected function _toHtml()
    {
        $additional = '
			<script type="text/javascript">
			jQuery(document).ready(function(){
				teamandtaskorganizerCommentGridJsObject.addVarToUrl("tab","teamandtaskorganizer_tasks_tabs_task_comments");
			});
			</script>
		';
        return $additional . parent::_toHtml();
    }

}
