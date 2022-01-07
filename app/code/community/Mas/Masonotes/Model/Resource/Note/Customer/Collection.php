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
 * Note - product relation resource model collection
 *
 * @category	Mas
 * @package		Mas_Masonotes
 * 
 */
class Mas_Masonotes_Model_Resource_Note_Customer_Collection extends Mage_Customer_Model_Resource_Customer_Collection {
	/**
	 * remember if fields have been joined
	 * @var bool
	 */
	protected $_joinedFields = false;
	/**
	 * join the link table
	 * @access public
	 * @return Mas_Masonotes_Model_Resource_Note_Customer_Collection
	 * 
	 */
	public function joinFields(){
		if (!$this->_joinedFields){
			$this->getSelect()->join(
				array('related' => $this->getTable('masonotes/note_customer')),
				'related.order_id = e.entity_id',
				array('position')
			);
			$this->_joinedFields = true;
		}
		return $this;
	}
	/**
	 * add note filter
	 * @access public
	 * @param Mas_Masonotes_Model_Note | int $note
	 * @return Mas_Masonotes_Model_Resource_Note_Customer_Collection
	 * 
	 */
	public function addNoteFilter($note){
		if ($note instanceof Mas_Masonotes_Model_Note){
			$note = $note->getId();
		}
		if (!$this->_joinedFields){
			$this->joinFields();
		}
		
		$this->getSelect()->where('related.note_id = ?', $note);
		return $this;
	}
}