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
 * Adminhtml observer
 *
 * @category	Mas
 * @package		Mas_Masonotes
 * 
 */
class Mas_Masonotes_Model_Adminhtml_Observer{
	/**
	 * check if tab can be added
	 * @access protected
	 * @param Mage_Catalog_Model_Product $product
	 * @return bool
	 * 
	 */
	protected function _canAddTab($product){
		if ($product->getId()){
			return true;
		}
		return false;
	}
	/**
	 * add the note tab to products
	 * @access public
	 * @param Varien_Event_Observer $observer
	 * @return Mas_Masonotes_Model_Adminhtml_Observer
	 * 
	 */
	public function addNoteBlock($observer){
		$block = $observer->getEvent()->getBlock();
		
		if ($block instanceof Mage_Adminhtml_Block_Sales_Order_View_Tabs){
			$id = Mage::registry('current_order');
			if ($id) {
				$prefix = Mage::helper('masonotes/note')->getNotesCount($id->getId());
				$block->addTabAfter('order_notes', array(
                	'label' => Mage::helper('masonotes')->__('Notes On Order (%d)', $prefix),
                    'url'   => Mage::helper('adminhtml')->getUrl('adminhtml/masonotes_note_customer/notes', array('order_id' => $id->getId(), 'is_order' => 1, 'website_id' => $id->getStoreId())),
					'class' => 'ajax',
                ), 'order_transactions');
			}
		}

		return $this;
	}

}