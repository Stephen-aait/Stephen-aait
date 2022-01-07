<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-05-08, 11:57:23)
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

class Modulesgarden_Teamandtaskorganizer_Model_Task_Auto extends Mage_Core_Model_Abstract {
	
	const STATUS_DISABLED = 0;
	const STATUS_ENABLED = 1;
	
	protected function _construct(){
		$this->_init('teamandtaskorganizer/task_auto');
	}
	
	/**
	 * Returns list of all supported events
	 * 
	 * To create new one:
	 * - create event in config.xml
	 * - implement event in Model/Observer.php
         * - create a new event model inside the /Model/Task/Events directory
         * - add reference inside the /Model/Task/Event file in the getTypes() method
	 * 
	 * @return array
	 */
	public function getEventsArray() {
            $eventsTypes    = Modulesgarden_Teamandtaskorganizer_Model_Task_Event::getTypes();
            $data           = array();
            
            foreach($eventsTypes as $name => $model) {
                $data[ $name ] = $model->getName();
            }
            
            return $data;
	}
	
	public function getEventVars($event = null){
		$event      = $event ?: $this->getEvent();
                $eventModel = Modulesgarden_Teamandtaskorganizer_Model_Task_Event::make($event);
                
                if($eventModel) {
                    return $eventModel->getVars();
                }
                
		return array();
	}
        
        public function setOrderStatusConditions($conditions) {
            if( ! empty($conditions) ) {
                $this->setData('order_status_conditions', base64_encode(serialize($conditions)));
            }
            
            return $this;
        }
        
        public function getOrderStatusConditions() {
            $conditions = $this->getData('order_status_conditions');
            
            if( ! empty($conditions) ) {
                return unserialize(base64_decode($conditions));
            }
            
            return null;
        }
	
	public function createTask(Varien_Object $obj){
		$event = $this->getEvent();
		$foreignId = $obj->getId() ? $obj->getId() : $obj->getEntityId();
                
                $eventModel = Modulesgarden_Teamandtaskorganizer_Model_Task_Event::make($event);
                
                if( ! ($eventModel AND $eventModel->isValid($obj)) ) {
                    throw new Exception('Unable to create auto task');
                }
                
                $foreignType = $eventModel->getForeignType();
                
                if($eventModel instanceof Modulesgarden_Teamandtaskorganizer_Model_Task_Events_AfterReview) {
                    $foreignId = $obj->getReviewId();
                }
		
		$task = Mage::getModel('teamandtaskorganizer/task');
		$task->setUserId( $this->getUserId() );
		$task->setCrdate( date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time())) );
		$task->setUpdate( date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time())) );
		$task->setTitle( $this->_getParsedField('title', $obj) );
		$task->setDescription( $this->_getParsedField('description', $obj) );
		$task->setPriority( $this->getPriority() );
		$task->setType( 1 );
		$task->setStatus( self::STATUS_ENABLED );
		$task->setForeignId( $foreignId );
		$task->setForeignType( $foreignType );
		$task->setNotify( $this->getNotify() );

		$admin = Mage::getSingleton('teamandtaskorganizer/user')
			->setNewUserId($this->getUserId());

		if ($admin->canGetEmail($task->getNotify())){
			$task->sendNotificationToOwner();
		}
		
		return $task->save();
	}
	
	protected function _getParsedField($field, Varien_Object $obj){
		$description = $this->getData($field);
		$vars = $this->getEventVars();
		foreach ($vars as $var){
			$description = str_replace('{$'.$var.'}', $obj->getData($var), $description);
		}
		return $description;
	}
	
}
