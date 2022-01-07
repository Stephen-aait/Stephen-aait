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
 * Note helper
 *
 * @category	Mas
 * @package		Mas_Masonotes
 * 
 */
class Mas_Masonotes_Helper_Note extends Mage_Core_Helper_Abstract{
	
	public function getNotesCount($customerId) {
		$db = Mage::getSingleton('core/resource');
		$tableName = $db->getTableName('masonotes/note_customer');
		$sql = sprintf("select count(rel_id) as cnt from `%s` where order_id = %d", $tableName, $customerId);
		$res = $db->getConnection('core_read')->fetchRow($sql);
		return $res['cnt'];
	}
	
	
	public function getCheckoutComment()
	{
		$insert = '<div class="buttons-set masonotes" style="text-align: left;">' .
					  '<b>' . $this->__('Your Comment') . '</b><br />' .
					  '<textarea style="width: 100%;" name="orderComment" id="orderComment"></textarea></div>';
		return $insert;
	}
	/**
	 * check if breadcrumbs can be used
	 * @access public
	 * @return bool
	 * 
	 */
	public function getUseBreadcrumbs(){
		return Mage::getStoreConfigFlag('masonotes/note/breadcrumbs');
	}
}