<?php
/**
 * @author      Sharad Patel
 * @category    Sale
 * @package     Expert_Orderdelete
 * @copyright   Copyright (c) 2013 expertsofttechsolution.com 
 */
class Expert_Orderdelete_Model_Mysql4_Orderdelete extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
	$configValue = Mage::getStoreConfig('sales/orderdelete/enabled');
	if($configValue==1){	
        // Note that the orderdelete_id refers to the key field in your database table.
        $this->_init('orderdelete/orderdelete', 'orderdelete_id');
		}
    }
}