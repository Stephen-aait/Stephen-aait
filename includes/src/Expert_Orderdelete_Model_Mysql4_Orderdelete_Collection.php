<?php
/**
 * @author      Sharad Patel
 * @category    Sale
 * @package     Expert_Orderdelete
 * @copyright   Copyright (c) 2013 expertsofttechsolution.com 
 */

class Expert_Orderdelete_Model_Mysql4_Orderdelete_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
		$configValue = Mage::getStoreConfig('sales/orderdelete/enabled');
		if($configValue==1){
        parent::_construct();
        $this->_init('orderdelete/orderdelete');
		}
    }
}