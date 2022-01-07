<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-05-07, 11:04:52)
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
class Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_Teamandtaskorganizer_managesuperadmin_Users_Grid
        extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('teamandtaskorganizerUsersGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _getCollectionClass()
    {
        return 'admin/user_collection';
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel($this->_getCollectionClass());
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {

        $this->addColumn('user_id', array(
            'header' => Mage::helper('admin')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'user_id',
        ));

        $this->addColumn('lastname', array(
            'header' => Mage::helper('admin')->__('Last Name'),
            'align' => 'left',
            'index' => 'lastname',
        ));

        $this->addColumn('firstname', array(
            'header' => Mage::helper('admin')->__('First Name'),
            'align' => 'left',
            'index' => 'firstname',
        ));

        $this->addColumn('email', array(
            'header' => Mage::helper('admin')->__('Email'),
            'align' => 'left',
            'index' => 'email',
        ));

        $this->addColumn('action', array(
            'header' => Mage::helper('admin')->__('Action'),
            'width' => '100',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('admin')->__('Privileges'),
                    'url' => array('base' => '*/*/edituserprivilege'),
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

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edituserprivilege', array('id' => $row->getId()));
    }

    protected function _toHtml()
    {
        $additional = '
			<script type="text/javascript">
			jQuery(document).ready(function(){
				teamandtaskorganizerUsersGridJsObject.addVarToUrl("tab","teamandtaskorganizer_managesuperadmin_tabs_user_privileges");
			});
			</script>
		';
        return $additional . parent::_toHtml();
    }

}
