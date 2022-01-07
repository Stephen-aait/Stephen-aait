<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-04-17, 14:00:07)
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

class Modulesgarden_Teamandtaskorganizer_Model_Widget {

	/**
	 * Default placement for task list widget.
	 */
	const PLACEMENT_BOTTOM = 1;

	/**
	 * Place task list widget to right side of the screen.
	 */
	const PLACEMENT_RIGHT = 2;

	/**
	 * Set position of widget into bottom right corner (like live chat).
	 */
	const PLACEMENT_CORNER = 3;

	/**
	 * Return list of options.
	 * @return array
	 */
	public static function getPositionOptions() {
		return array(
			self::PLACEMENT_BOTTOM	=> Mage::helper('teamandtaskorganizer')->__('Bottom'),
			self::PLACEMENT_RIGHT	=> Mage::helper('teamandtaskorganizer')->__('Right'),
			self::PLACEMENT_CORNER	=> Mage::helper('teamandtaskorganizer')->__('Corner (Live Chat Style')
		);
	}

	/**
	 * Return css class for all positions, or if <var>$position</var> is given, return only one.
	 * 
	 * @param int $position
	 * @return string|array
	 */
	public static function getPositionCss($position = null) {
		$positions = array(
			self::PLACEMENT_BOTTOM	=> 'bottom',
			self::PLACEMENT_RIGHT	=> 'right',
			self::PLACEMENT_CORNER	=> 'corner'
		);
		if ($position) {
			return $positions[$position];
		}
		return $positions;
	}

	/**
	 * Return default position option.
	 * @return int
	 */
	public static function getDefaultPosition() {
		return self::PLACEMENT_BOTTOM;
	}

}
