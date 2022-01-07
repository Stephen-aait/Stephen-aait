<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-11-10, 10:46:06)
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
class Modulesgarden_Base_Model_System_Config_Source_Notificationevents {

	CONST UPGRADES = 'upgrade';
	CONST RELEASES = 'release';
	CONST PROMOTIONS = 'promotions';
	CONST OTHER = 'other';
	
	public function toOptionArray() {
		$h = Mage::helper('modulesgardenbase');
		return array(
			array('value' => self::UPGRADES,	'label' => $h->__('Upgrade Is Ready')),
			array('value' => self::RELEASES,	'label' => $h->__('ModulesGarden Release New Extension')),
			array('value' => self::PROMOTIONS,	'label' => $h->__('Promotions/Discounts')),
			array('value' => self::OTHER,		'label' => $h->__('Other Important Messages')),
		);
	}

}
