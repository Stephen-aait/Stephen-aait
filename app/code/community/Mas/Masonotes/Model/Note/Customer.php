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
 * Note product model
 *
 * @category	Mas
 * @package		Mas_Masonotes
 * 
 */
class Mas_Masonotes_Model_Note_Customer extends Mage_Core_Model_Abstract{
	/**
	 * Initialize resource
	 * @access protected
	 * @return void
	 * 
	 */
	protected function _construct(){
		$this->_init('masonotes/note_customer');
	}
	/**
	 * Save data for note-product relation
	 * @access public
	 * @param  Mas_Masonotes_Model_Note $note
	 * @return Mas_Masonotes_Model_Note_Product
	 * 
	 */
	public function saveNoteRelation($note){
		$data = $note->getProductsData();
		if (!is_null($data)) {
			$this->_getResource()->saveNoteRelation($note, $data);
		}
		return $this;
	}
	/**
	 * get products for note
	 * @access public
	 * @param Mas_Masonotes_Model_Note $note
	 * @return Mas_Masonotes_Model_Resource_Note_Product_Collection
	 * 
	 */
	public function getProductCollection($note){
		$collection = Mage::getResourceModel('masonotes/note_customer_collection')
			->addNoteFilter($note);
		return $collection;
	}
}