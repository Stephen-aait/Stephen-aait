<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-05-07, 11:03:40)
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
class Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_Teamandtaskorganizer_Managesuperadmin_Users
        extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_blockGroup = 'teamandtaskorganizer';
        $this->_controller = 'adminhtml_teamandtaskorganizer_managesuperadmin_users';
        $this->_headerText = Mage::helper('teamandtaskorganizer')->__('Users Privileges');
        parent::__construct();

        $this->_removeButton('add');

        // modulesgarden template
        $this->setTemplate('modulesgardenbase/widget/grid/container.phtml');
        $this->setSkipHeaderCopy(true);
    }

    protected function _toHtml()
    {
        return '<div class="main-col-inner">' . parent::_toHtml() . '</div>';
    }

}
