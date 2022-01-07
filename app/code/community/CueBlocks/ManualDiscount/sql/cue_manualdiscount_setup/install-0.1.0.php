<?php
/**
 * @company   CueBlocks - http://www.cueblocks.com/
 * @author    Ravinder <ravinder.singh@cueblocks.com>
 */

$installer = new Mage_Sales_Model_Resource_Setup('core_setup');
/**
 * Add 'custom_attribute' attribute for entities
 */
$entities = array('quote','order');
foreach ($entities as $entity) {
    $options = array(
    'type'     => Varien_Db_Ddl_Table::TYPE_VARCHAR,
    'visible'  => true,
    'required' => false
    );
    $installer->addAttribute(
        $entity, 'manual_discount', array(
        'type'     => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'visible'  => true,
        'required' => false
        )
    );
    $installer->addAttribute($entity, 'manual_discount_type', $options);
}

$installer->endSetup();
