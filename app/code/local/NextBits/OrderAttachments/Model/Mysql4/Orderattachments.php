<?php
class NextBits_OrderAttachments_Model_Mysql4_Orderattachments extends Mage_Core_Model_Mysql4_Abstract
{
	protected function _construct(){
		$this->_init('orderattachments/orderattachments','order_attachments_id');
	}
	
	protected function _beforeSave(Mage_Core_Model_Abstract $object){
		if(!$object->getCreatedOn()) {
			$object->setCreatedOn($this->formatDate(time()));
		}
		$object->setUpdatedOn($this->formatDate(time()));
        parent::_beforeSave($object);
	}
}
?>