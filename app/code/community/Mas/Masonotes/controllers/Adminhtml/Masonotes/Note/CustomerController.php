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
 * Note product admin controller
 *
 * @category	Mas
 * @package		Mas_Masonotes
 * 
 */
require_once ("Mage/Adminhtml/controllers/CustomerController.php");
class Mas_Masonotes_Adminhtml_Masonotes_Note_CustomerController extends Mage_Adminhtml_CustomerController{
	/**
	 * construct
	 * @access protected
	 * @return void
	 * 
	 */
	protected function _construct(){	
		$this->setUsedModuleName('Mas_Masonotes');
	}
	/**
	 * notes in the catalog page
	 * @access public
	 * @return void
	 * 
	 */
	public function notesAction(){
		$this->loadLayout();		
		$this->renderLayout();
	}	
}