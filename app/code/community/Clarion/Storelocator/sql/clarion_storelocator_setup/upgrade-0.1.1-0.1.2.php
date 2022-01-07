<?php
/**
 * Storelocator installation script
 * 
 * @category    Clarion
 * @package     Clarion_Storelocator
 * @author      Clarion Magento Team <magento@clariontechnologies.co.in>
 * 
 */
/** @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

//Add unique index for column 'name'
$installer->getConnection()->addIndex(
        $installer->getTable('clarion_storelocator/storelocator'),
        $installer->getIdxName(
                'clarion_storelocator/storelocator', 
                array('name'), 
                Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
         ),
        array('name'), 
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
);

$installer->endSetup();