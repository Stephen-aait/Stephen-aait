<?php
/**
 * Storelocator data installation script
 * 
 * @category    Clarion
 * @package     Clarion_Storelocator
 * @author     Clarion Magento Team <magento@clariontechnologies.co.in>
 * 
 */
/**
 * @var $installer Mage_Core_Model_Resource_Setup
 */
$installer = $this;

/**
 * Update table 'clarion_storelocator/storelocator_store' for multiple store view
 */

//test stores in the table 'clarion_storelocator/storelocator' 
$testStores = array('Demo1', 'Demo2', 'Demo3', 'Demo4', 'Demo5');

foreach ($testStores as $testStore) {
    $testStorelocator = Mage::getModel('clarion_storelocator/storelocator')->load($testStore,'name');
    if ($testStorelocator->getId()) {
        $stores = $testStorelocator->getStores();
        if(empty($stores)){
            $testStorelocator->setStores(array(0))->save();
        }
    }
}