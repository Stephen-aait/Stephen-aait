<?php
/**
 * Mas_Masonotes extension by Makarovsoft.com
 * 
 * @category   	Mas
 * @package		Mas_Masonotes
 * @copyright  	Copyright (c) 2014
 * @license		http://makarovsoft.com/license.txt
 * @author		makarovsoft.com
 */
/**
 * Note admin block
 *
 * @category	Mas
 * @package		Mas_Masonotes
 * 
 */
class Mas_Masonotes_Block_Adminhtml_Note extends Mage_Adminhtml_Block_Widget_Grid_Container{
	/**
	 * constructor
	 * @access public
	 * @return void
	 * 
	 */
	public function __construct(){
		$this->_controller 		= 'adminhtml_note';
		$this->_blockGroup 		= 'masonotes';
		$this->_headerText 		= Mage::helper('masonotes')->__('Note');
		$this->_addButtonLabel 	= Mage::helper('masonotes')->__('Add Note');
		parent::__construct();
	}
}