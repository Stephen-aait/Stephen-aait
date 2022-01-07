<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-05-13, 15:10:16)
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

class Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_Statistics_Stats extends Mage_Core_Block_Template {

	protected $_user;
	
	public function _construct(){
		$this->setTemplate('teamandtaskorganizer/statistics/stats.phtml');
		$this->_user = Mage::registry('tto_stats_user');
		
		if (!$this->_user)
			Mage::throwException('Unable to get stats');
	}
	
	public function getUser(){
		return $this->_user;
	}
	
	public function getStatsArray(){
		return $this->_user->getStatsArray();
	}
	
	public function getOtherAdmins(){
		$user = Mage::getSingleton('teamandtaskorganizer/user');
		$privileges = $user->isAllowed('STATISTICS');
		if (!($privileges == -1 || (is_array($privileges) && in_array($this->_user->getUserId(), $privileges)))){
			return array();
		}
		$arr = array();
		$collection = Mage::getModel('admin/user')->getCollection();
		foreach ($collection as $item){
			$arr[$item->getUserId()] = $item->getLastname() . ' ' . $item->getFirstname();
		}
		return $arr;
	}
	
}