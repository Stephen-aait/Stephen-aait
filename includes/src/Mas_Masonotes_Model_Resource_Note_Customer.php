<?php 
/**
 * Mas_Masnotes extension by Makarovsoft.com
 * 
 * @category   	Mas
 * @package		Mas_Masnotes
 * @copyright  	Copyright (c) 2014
 * @license		http://makarovsoft.com/license.txt
 * @author		makarovsoft.com
 */
/**
 * Note - customer relation model
 *
 * @category	Mas
 * @package		Mas_Masnotes
 * 
 */
class Mas_Masonotes_Model_Resource_Note_Customer extends Mage_Core_Model_Resource_Db_Abstract{
/**
	 * initialize resource model
	 * @access protected
	 * @return void
	 * @see Mage_Core_Model_Resource_Abstract::_construct()
	 * 
	 */
	protected function  _construct(){
		$this->_init('masonotes/note_customer', 'rel_id');
	}
	/**
	 * Save note - product relations
	 * @access public
	 * @param Mas_Masnotes_Model_Note $note
	 * @param array $data
	 * @return Mas_Masonotes_Model_Resource_Note_Customer
	 * 
	 */
	public function saveNoteRelation($note, $data){
		if (!is_array($data)) {
			$data = array();
		}
		
		$deleteCondition = $this->_getWriteAdapter()->quoteInto('note_id=?', $note->getId());
		$this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

		foreach ($data as $customerId => $info) {
			$this->_getWriteAdapter()->insert($this->getMainTable(), array(
				'note_id'  	=> $note->getId(),
				'order_id' 	=> $info,
			));
		}
		return $this;
	}
	
	public function saveSingleProductRelation($data){
		if (!is_array($data)) {
			$data = array();
		}
		foreach ($data as $info) {
			$this->_getWriteAdapter()->insert($this->getMainTable(), array(
				'note_id'  	=> 		$info['note_id'],
				'order_id' 	=> $info['customer_id'],
			));
		}
		return $this;
	}
	
	public function deleteCustomerRelation($noteId, $customerId){
		
		$deleteCondition = array(
			'order_id = ?' => $customerId, 
			'note_id = ?' => $noteId
		);
		$this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);
		return $this;
	}
	
	/**
	 * Save  product - note relations
	 * @access public
	 * @param Mage_Catalog_Model_Product $prooduct
	 * @param array $data
	 * @return Mas_Masonotes_Model_Resource_Note_Customer
	 * @
	 */
	public function saveCustomerRelation($customer, $data){
		if (!is_array($data)) {
			$data = array();
		}
		$deleteCondition = $this->_getWriteAdapter()->quoteInto('order_id=?', $customer->getId());
		$this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);
		
		foreach ($data as $noteId => $info) {
			$this->_getWriteAdapter()->insert($this->getMainTable(), array(
				'note_id' => $noteId,
				'order_id' => $customer->getId(),
			));
		}
		return $this;
	}
}