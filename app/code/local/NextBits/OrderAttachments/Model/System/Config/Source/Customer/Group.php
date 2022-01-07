<?php
class NextBits_OrderAttachments_Model_System_Config_Source_Customer_Group
{
	protected $_options;
	
	public function toOptionArray(){
		if(!$this->_options) {
			$this->_options = Mage::getResourceModel('customer/group_collection')
				->setRealGroupsFilter()
				->loadData()->toOptionArray();
			array_unshift($this->_options,array('value' => '0', 'label' => Mage::helper('orderattachments')->__('Not logged in')));
		}
		return $this->_options;
	}
}
?>