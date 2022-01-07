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
 * Note tab on product edit form
 *
 * @category	Mas
 * @package		Mas_Masonotes
 * 
 */
class Mas_Masonotes_Block_Notes extends Mage_Core_Block_Template {
	/**
	 * Set grid params
	 * @access protected
	 * @return void
	 * 
	 */
	public function __construct(){
		parent::__construct();        
	}
	/**
	 * prepare the note collection
	 * @access protected 
	 * @return Mas_Masonotes_Block_Adminhtml_Catalog_Product_Edit_Tab_Note
	 * 
	 */
	public function getCollection() {
		$collection = Mage::getResourceModel('masonotes/note_collection');
		$collection->addFieldToFilter('status', 1);
		
		if ($this->getOrderId()){
			$constraint = 'related.order_id='.$this->getOrderId();
		} else {
			$constraint = 'related.customer_id=0';
		}
		$collection->getSelect()->joinRight(
			array('related'=>$collection->getTable('masonotes/note_customer')),
			'related.note_id=main_table.entity_id AND '.$constraint,
			array('position')
		);
		$collection->getSelect()->joinLeft(
			array('user'=>$collection->getTable('admin/user')),
			'user.user_id=main_table.user_id',
			array('firstname', 'lastname')
		);
		
		return $collection;		
	}
	
	public function getOrderId() {
		
		if ($this->hasData('order_id')) {
			return $this->getData('order_id');
		}
		return Mage::registry('current_order')->getId();
	}
	
	public function getStoreId() {
		return Mage::registry('current_order')->getStoreId();
	}
	
}

