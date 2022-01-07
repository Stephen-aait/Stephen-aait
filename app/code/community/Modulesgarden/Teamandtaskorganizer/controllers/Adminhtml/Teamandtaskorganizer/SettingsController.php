<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-05-13, 14:09:18)
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
class Modulesgarden_Teamandtaskorganizer_Adminhtml_Teamandtaskorganizer_SettingsController
        extends Modulesgarden_Teamandtaskorganizer_Controller_Action
{

    protected function _isAllowed()
    {
        return true;
        $s = Mage::getSingleton('admin/session');
        return $s->isAllowed('teamandtaskorganizer/teamandtaskorganizer_managesuperadmin') || $s->isAllowed('teamandtaskorganizer/teamandtaskorganizer_task');
    }

    public function saveAction()
    {
        // @todo ACL
        $post = $this->getRequest()->getPost();
        $user = Mage::getSingleton('teamandtaskorganizer/user');

        foreach (array('WIDGET_PLACEMENT', 'SEND_NOTIFICATION', 'REFRESH_FREQUENCY') as $setting) {
            $collection = Mage::getModel('teamandtaskorganizer/user_setting')->getCollection()
                    ->addFieldToFilter('user_id', $user->getUserId())
                    ->addFieldToFilter('settingkey', $setting);

            if ($collection->getSize()) {
                foreach ($collection as $sett) {
                    $sett->setValue($post[$setting]);
                    $sett->save();
                }
            } else {
                $s = Mage::getModel('teamandtaskorganizer/user_setting')
                        ->setUserId($user->getUserId())
                        ->setSettingkey($setting)
                        ->setValue($post[$setting]);
                $s->save();
            }
        }

        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('teamandtaskorganizer')->__('Settings have been saved.'));
        return $this->_redirect('adminhtml/teamandtaskorganizer_tasks/index', array(
                    'tab' => 'teamandtaskorganizer_tabs_settings'
        ));
    }

}
