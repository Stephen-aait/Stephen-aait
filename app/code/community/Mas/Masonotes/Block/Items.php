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
class Mas_Masonotes_Block_Items extends Mage_Core_Block_Template {
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
		if (!$this->hasData('notes_collection')) {
			$collection = Mage::getResourceModel('masonotes/note_collection');
			$collection->addFieldToFilter('main_table.status', 1);
			
			if ($this->getCustomerId()){
				$constraint = 'related2.customer_id ='.$this->getCustomerId();
			}
			$collection->getSelect()->joinRight(
				array('related'=>$collection->getTable('masonotes/note_customer')),
				'related.note_id=main_table.entity_id',
				array('position')
			);
			$collection->getSelect()->joinLeft(
				array('user'=>$collection->getTable('admin/user')),
				'user.user_id=main_table.user_id',
				array('firstname', 'lastname')
			);
			
			$collection->getSelect()->joinRight(
				array('related2'=>$collection->getTable('sales/order')),
				'related2.entity_id=related.order_id AND '.$constraint,
				array('related.order_id', 'related2.increment_id')
			);		
			$collection->getSelect()->group('entity_id');
			$collection->getSelect()->order(array('order_id desc', 'entity_id asc'));
			$this->setData('notes_collection', $collection);
		}
		
		return $this->getData('notes_collection');		
	}
	
	protected function _prepareLayout(){
		parent::_prepareLayout();
		$pager = $this->getLayout()->createBlock('page/html_pager', 'masonotes.html.pager')
			->setCollection($this->getCollection());
		$this->setChild('pager', $pager);
//		$this->getCollection()->load();
		return $this;
	}
	
	public function getPagerHtml(){
		return $this->getChildHtml('pager');
	}
	
	public function getCustomerId(){
		return Mage::getSingleton('customer/session')->getCustomer()->getId();
	}
	
}

