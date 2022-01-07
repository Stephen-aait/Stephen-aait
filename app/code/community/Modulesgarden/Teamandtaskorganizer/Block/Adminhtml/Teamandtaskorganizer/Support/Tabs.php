<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-06-17, 11:08:04)
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
class Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_Teamandtaskorganizer_Support_Tabs
        extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('teamandtaskorganizer_support_tabs');
        $this->setTitle(Mage::helper('teamandtaskorganizer')->__('Support'));

        // modulesgarden template
        $this->setTemplate('modulesgardenbase/widget/tabs.phtml');
    }

    protected function _beforeToHtml()
    {
        $layout = $this->getLayout();

        $this->addTab('open_ticket', array(
            'label' => Mage::helper('teamandtaskorganizer')->__('Open Support Ticket'),
            'content' => $layout->createBlock('teamandtaskorganizer/adminhtml_teamandtaskorganizer_support_edit')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }

}
