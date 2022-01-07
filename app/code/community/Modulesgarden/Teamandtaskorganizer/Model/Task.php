<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-04-15, 16:06:32)
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
class Modulesgarden_Teamandtaskorganizer_Model_Task extends Mage_Core_Model_Abstract
{

    /**
     * Low priority for task
     */
    const PRIORITY_LOW = 0;

    /**
     * Normal priority for task
     */
    const PRIORITY_MEDIUM = 1;

    /**
     * High priority for task
     */
    const PRIORITY_HIGH = 2;

    /**
     * Urget priority for task
     */
    const PRIORITY_URGENT = 3;

    /**
     * Status - pending
     */
    const STATUS_PENDING = 1;

    /**
     * Status - in progress
     */
    const STATUS_PROGRESS = 5;

    /**
     * Status - done
     */
    const STATUS_DONE = 10;

    /**
     * Task created automaticly from event or something
     */
    const TYPE_AUTO = 1;

    /**
     * Task created manually by user
     */
    const TYPE_NORMAL = 2;

    /**
     * Enable email notify
     */
    const NOTIFY_ENABLED = 1;

    /**
     * Disable email notify
     */
    const NOTIFY_DISABLED = 0;
    const EMAIL_TO_OWNER_AFTER_TASK = 'TTO New Task Assigned To You';
    const EMAIL_TO_OWNER_AFTER_COMMENT = 'TTO New Comment In Your Task';

    protected function _construct()
    {
        $this->_init('teamandtaskorganizer/task');
    }

    public function getStatusStr()
    {
        return self::getStatusMap($this->getStatus());
    }

    public function getPriorityStr()
    {
        return self::getPriorityMap($this->getPriority());
    }

    public function getStartDateStr()
    {
        return Mage::helper('core')->formatDate($this->getStartdate(), 'medium', false);
    }

    public function getDeadlineStr()
    {
        return Mage::helper('core')->formatDate($this->getEndate(), 'medium', false);
    }

    public function getForeignItemName()
    {
        return Modulesgarden_Teamandtaskorganizer_Model_Foreign::make($this)->getName();
    }

    public function getForeignItemUrl()
    {
        return Modulesgarden_Teamandtaskorganizer_Model_Foreign::make($this)->getUrl();
    }

    /**
     * Return array map with priorities list.
     *
     * @param string $key when is defined, then return only this status or all results if not exists
     * @return array
     */
    public static function getPriorityMap($key = null)
    {
        $array = array(
            self::PRIORITY_LOW => Mage::helper('teamandtaskorganizer')->__('Low'),
            self::PRIORITY_MEDIUM => Mage::helper('teamandtaskorganizer')->__('Medium'),
            self::PRIORITY_HIGH => Mage::helper('teamandtaskorganizer')->__('High'),
            self::PRIORITY_URGENT => Mage::helper('teamandtaskorganizer')->__('Urgent')
        );
        if (!is_null($key) && isset($array[$key])) {
            return $array[$key];
        }
        return $array;
    }

    /**
     * Return array with statuses map.
     *
     * @param string $key when is defined, then return only this status or all results if not exists
     * @return array
     */
    public static function getStatusMap($key = null)
    {
        $array = array(
            self::STATUS_PENDING => Mage::helper('teamandtaskorganizer')->__('Pending'),
            self::STATUS_PROGRESS => Mage::helper('teamandtaskorganizer')->__('In Progress'),
            self::STATUS_DONE => Mage::helper('teamandtaskorganizer')->__('Done')
        );
        if (!is_null($key) && isset($array[$key])) {
            return $array[$key];
        }
        return $array;
    }

    public function getCommentsCollection()
    {
        $coll = Mage::getModel('teamandtaskorganizer/task_comment')->getCollection()
                ->addFieldToFilter('task_id', $this->getId());
        $coll->getSelect()->order('crdate DESC');
        return $coll;
    }

    public function sendNotificationToOwner()
    {
        $emailTemplate = Mage::getModel('core/email_template')
                ->loadByCode(self::EMAIL_TO_OWNER_AFTER_TASK);

        // magento bug
        $emailTemplate->setSenderName($emailTemplate->getTemplateSenderName());
        $emailTemplate->setSenderEmail($emailTemplate->getTemplateSenderEmail());
        $emailTemplate->setTemplateSubject($emailTemplate->getTemplateSubject());

        // set details from main config instead of template
        $conf = Mage::getStoreConfig('trans_email/ident_general');
        if (isset($conf['name']) && isset($conf['email'])) {
            $emailTemplate->setSenderName($conf['name']);
            $emailTemplate->setSenderEmail($conf['email']);
        }

        $user = Mage::getModel('admin/user')->load($this->getUserId());
        $vars = array(
            'task_user' => $user->getFirstname() . ' ' . $user->getLastname(),
            'task_title' => $this->getTitle(),
            'task_description' => nl2br($this->getDescription()),
            'task_priority' => $this->getPriorityMap($this->getPriority()),
            'task_status' => $this->getStatusMap($this->getStatus()),
            'task_startdate' => substr($this->getStartdate(), 0, 10),
            'task_deadline' => substr($this->getEnddate(), 0, 10),
            'task_crdate' => substr($this->getCrdate(), 0, 10)
        );

        return $emailTemplate->send($user->getEmail(), $user->getLastname() . ' ' . $user->getFirstname(), $vars);
    }

    public static function getCommentsStatsArray($user_id)
    {
        return Modulesgarden_Teamandtaskorganizer_Model_Mysql4_Task::getCommentsStatsArray($user_id);
    }

    public function getDataForUser($user)
    {
        $commentsStats = $this->getCommentsStatsArray($user->getUserId());
        $title = $this->getTitle();

        if ($this->getForeignId() && $this->getForeignType()) {
            $href = Modulesgarden_Teamandtaskorganizer_Model_Foreign::make($this)->getUrl();
            $title .= ' <a href="' . $href . '">' . $this->getForeignItemName() . '</a>';
        }

        return array(
            'id' => $this->getId(),
            'title' => $title,
            'description' => $this->getDescription(),
            'priority' => $this->getPriority(),
            'priorityText' => Mage::helper('teamandtaskorganizer')->__($this->getPriorityMap($this->getPriority())),
            'status' => $this->getStatus(),
            'statusText' => Mage::helper('teamandtaskorganizer')->__($this->getStatusMap($this->getStatus())),
            'read_by_owner' => $this->getReadByOwner(),
            'comments_total' => isset($commentsStats[$this->getId()]) ? $commentsStats[$this->getId()]['total'] : 0,
            'comments_unread' => isset($commentsStats[$this->getId()]) ? $commentsStats[$this->getId()]['unread'] : 0,
        );
    }

    public function getStartdate()
    {
        if ($this->getData('startdate')) {
            $date = new DateTime($this->getData('startdate'));
            return $date->format('Y-m-d');
        }

        return null;
    }

    public function getEnddate()
    {
        if ($this->getData('enddate')) {
            $date = new DateTime($this->getData('enddate'));
            return $date->format('Y-m-d');
        }

        return null;
    }

}
