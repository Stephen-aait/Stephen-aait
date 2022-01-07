<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-05-07, 12:33:30)
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
class Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_Teamandtaskorganizer_Managesuperadmin_Users_Edit_Form
        extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setUseContainer(true);
        $form->setId('edit_form');
        $user = Mage::registry('tto_privileges_user');
        $permissionsMap = Mage::getModel('teamandtaskorganizer/user_privilege')->getPermissionsMap();

        $admins = Modulesgarden_Teamandtaskorganizer_Model_User::getList(false);
        $adminsList = array(array(
                'value' => -1,
                'label' => Mage::helper('teamandtaskorganizer')->__("All")
        ));
        foreach ($admins as $id => $name) {
            $adminsList[] = array(
                'value' => $id,
                'label' => $name,
            );
        }

        foreach ($permissionsMap as $groupId => $group) {
            $fieldset = $form->addFieldset($groupId, array(
                'legend' => '<input type="checkbox" class="check_all_privileges" /> ' . $group['name'],
            ));

            foreach ($group as $permissionId => $permissionName) {
                if ($permissionId == 'name')
                    continue;

                if ($permissionId === 'SEE_OTHER_TASK' || $permissionId === 'STATISTICS') {
                    $fieldset->addField($permissionId, 'multiselect', array(
                        'name' => $permissionId . '[]',
                        'label' => $permissionName,
                        'values' => $adminsList,
                        'value' => $user->isAllowed($permissionId),
                    ));
                } else {
                    $fieldset->addField($permissionId, 'checkbox', array(
                        'name' => $permissionId,
                        'label' => $permissionName,
                        'checked' => (bool) $user->isAllowed($permissionId)
                    ));
                }
            }
        }

        $fieldset->addField('user_id', 'hidden', array(
            'name' => 'user_id',
            'value' => $user->getUserId()
        ));

        $form->setAction($this->getUrl('*/*/saveuserprivilege'))
                ->setMethod('post');

        $this->setForm($form);
        return parent::_prepareForm();
    }

    protected function _toHtml()
    {
        $t = 'Every user has permission to view details of his own tasks and edit progress with status.';
        $additionalHtml = '
			<ul class="messages"><li class="notice-msg"><ul><li><span>' . Mage::helper('teamandtaskorganizer')->__($t) . '</span></li></ul></li></ul>
		';
        return $additionalHtml . parent::_toHtml();
    }

}
