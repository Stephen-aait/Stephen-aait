<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-05-29, 11:38:57)
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
 * @author Marcin Kozak <marcin.ko@modulesgarden.com>
 */

class Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_Teamandtaskorganizer_Tasks_Comments_Preview extends Mage_Adminhtml_Block_Template {
    public $task;
    public $user;
    
    protected $comments;
    
    public function _construct() {
        $this->user = Mage::getSingleton('teamandtaskorganizer/user');
        $this->setTemplate('teamandtaskorganizer/comments.phtml');
    }
    
    public function getAddCommentUrl() {
        return Mage::helper('adminhtml')->getUrl('teamandtaskorganizer/adminhtml_task/saveComment');
    }

    public function getTask() {
        return $this->task;
    }
    
    public function canPostComment() {
        return $this->user->isAllowedToAddComment($this->task);
    }
    
    public function getComments() {
        if( is_null($this->comments) ) {
            $users          = Modulesgarden_Teamandtaskorganizer_Model_User::getList(false);
            $this->comments = $this->task->getCommentsCollection();
                        
            foreach($this->comments as $comment){
                $comment->setUser( $users[$comment->getUserId()] );
                
                if ( ! $comment->getReadByTaskOwner()){
                    $comment->setReadByTaskOwner(1);
                    $comment->save();
                }
            }
        }
        
        return $this->comments;
    }
    
    public function hasComments() {
        return count( $this->getComments() );
    }
}
