<?php
/**
 * Storelocator installation script
 * 
 * @category    Clarion
 * @package     Clarion_Storelocator
 * @author      Clarion Magento Team <magento@clariontechnologies.co.in>
 * 
 */
/**
 * @var $installer Mage_Core_Model_Resource_Setup
 */
$installer = $this;

/**
 * Prepare database for install
 */
$installer->startSetup();

/**
 * Create table 'clarion_storelocator/storelocator'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('clarion_storelocator/storelocator'))
    ->addColumn('storelocator_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Storelocator Id')
        
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        ), 'Store Name')
        
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_TINYINT, null, array(
        'nullable'  => false,
        'default'   => '1',
        ), 'Staus')
        
   ->addColumn('street_address', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => true,
        'default'   => null,
        ), 'Street Address')
        
   ->addColumn('country', Varien_Db_Ddl_Table::TYPE_TEXT, 64, array(
        'nullable'  => true,
        'default'   => null,
        ), 'Country')
        
    ->addColumn('state', Varien_Db_Ddl_Table::TYPE_TEXT, 64, array(
        'nullable'  => true,
        'default'   => null,
        ), 'State')
   
    ->addColumn('city', Varien_Db_Ddl_Table::TYPE_TEXT, 64, array(
        'nullable'  => true,
        'default'   => null,
        ), 'City')
        
   ->addColumn('zipcode', Varien_Db_Ddl_Table::TYPE_TEXT, 64, array(
        'nullable'  => true,
        'default'   => null,
        ), 'Zipcode')
        
   ->addColumn('phone', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => true,
        'default'   => null,
        ), 'Phone')
   
   ->addColumn('fax', Varien_Db_Ddl_Table::TYPE_TEXT, 64, array(
        'nullable'  => true,
        'default'   => null,
        ), 'Fax')
   
   ->addColumn('url', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => true,
        'default'   => null,
        ), 'Store Url')
   
   ->addColumn('email', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => true,
        'default'   => null,
        ), 'Email')
   
   ->addColumn('store_logo', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => true,
        'default'   => null,
        ), 'Store Logo')
        
   ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, '64K', array(
        'nullable'  => true,
        'default'   => null,
        ), 'Description')
        
   ->addColumn('trading_hours', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => true,
        'default'   => null,
        ), 'Trading Hours')
   
   ->addColumn('radius', Varien_Db_Ddl_Table::TYPE_TEXT, 64, array(
       'nullable' => true,
       'default'  => null,
        ), 'Radius')
        
   ->addColumn('latitude', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
       'nullable' => true,
       'default'  => null,
        ), 'Latitude')
        
   ->addColumn('longitude', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
       'nullable' => true,
       'default'  => null,
        ), 'Longitude')
  
   ->addColumn('zoom_level', Varien_Db_Ddl_Table::TYPE_TEXT, 64, array(
       'nullable' => true,
       'default'  => null,
        ), 'Zoom level')
        
  ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
       'nullable' => true,
       'default'  => null,
        ), 'Creation Time')
   
  ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
       'nullable' => true,
       'default'  => null,
        ), 'Store Updated Date')
 
  //Add unique index for column 'name'
  ->addIndex(
        $installer->getIdxName(
            'clarion_storelocator/storelocator', 
            array('name'), 
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('name'), 
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
        
    ->setComment('Clarion Storelocator Table');

$installer->getConnection()->createTable($table);

/**
 * Create table 'clarion_storelocator/storelocator_store'
 */

$table = $installer->getConnection()
    ->newTable($installer->getTable('clarion_storelocator/storelocator_store'))
    ->addColumn('storelocator_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
        'primary'   => true,
        ), 'Storelocator ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Store ID')
    ->addIndex($installer->getIdxName('clarion_storelocator/storelocator_store', array('store_id')),
        array('store_id'))
        
    ->addForeignKey($installer->getFkName('clarion_storelocator/storelocator_store', 'storelocator_id', 'clarion_storelocator/storelocator', 'storelocator_id'),
        'storelocator_id', $installer->getTable('clarion_storelocator/storelocator'), 'storelocator_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
        
    ->addForeignKey($installer->getFkName('clarion_storelocator/storelocator_store', 'store_id', 'core/store', 'store_id'),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
        
    ->setComment('Clarion Storelocator To Store Linkage Table');
$installer->getConnection()->createTable($table);

/**
 * Prepare database after install
 */
$installer->endSetup();