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
class Mas_Masonotes_Block_Adminhtml_Customer_Edit_Tab_Note extends Mage_Adminhtml_Block_Widget_Grid{
	/**
	 * Set grid params
	 * @access protected
	 * @return void
	 * 
	 */
	public function __construct(){
		parent::__construct();
        $this->setTemplate('mas_masonotes/notes.phtml');		
	}
	/**
	 * prepare the note collection
	 * @access protected 
	 * @return Mas_Masonotes_Block_Adminhtml_Catalog_Product_Edit_Tab_Note
	 * 
	 */
	protected function _prepareCollection() {
		$collection = Mage::getResourceModel('masonotes/note_collection');
		if ($this->getOrderId()){
			$constraint = 'related.order_id='.$this->getOrderId();
			}
			else{
				$constraint = 'related.order_id=0';
			}
		$collection->getSelect()->joinLeft(
			array('related'=>$collection->getTable('masonotes/note_customer')),
			'related.note_id=main_table.entity_id',
			array('position')
		);
		
		$collection->getSelect()->joinLeft(
			array('user'=>$collection->getTable('admin/user')),
			'user.user_id=main_table.user_id',
			array('firstname', 'lastname')
		);
		$collection->getSelect()->where($constraint);
		
		$collection->setOrder(Mage::app()->getRequest()->getParam('sort', 'created_at'), Mage::app()->getRequest()->getParam('dir', 'desc'));
		
		$this->setCollection($collection);
		parent::_prepareCollection();
		return $this;
	}
	
	public function getStoreId()
	{
		$r = Mage::app()->getRequest();
		if ($r->getParam('website_id')) {
			return $r->getParam('website_id');
		}
	}
	
	public function getOrderId(){
		$r = Mage::app()->getRequest();
		if ($r->getParam('is_order')) {
//			$this->setData('is_order', 1);
		}
		if ($r->getParam('order_id')) {
			return $r->getParam('order_id');
		}
	}
	
	public function getOrder()	
	{
		return Mage::getModel('sales/order')->load($this->getOrderId());
	}
	
	public function getCustomer()
	{
		$order = $this->getOrder();
		return Mage::getModel('customer/customer')->load($order->getCustomerId());
	}
	
}