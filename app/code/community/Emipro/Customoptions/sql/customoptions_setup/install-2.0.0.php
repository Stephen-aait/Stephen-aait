<?php

$installer = $this;
$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('emipro_customoptions/product_option'))
    ->addColumn('option_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Option ID')

    ->addColumn('type', Varien_Db_Ddl_Table::TYPE_TEXT, 50, array(
        'nullable'  => true,
        'default'   => null,
        ), 'Type')
    ->addColumn('is_require', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
        'default'   => '1',
        ), 'Is Required')
    ->addColumn('sku', Varien_Db_Ddl_Table::TYPE_TEXT, 64, array(
        ), 'SKU')
    ->addColumn('max_characters', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        ), 'Max Characters')
    ->addColumn('file_extension', Varien_Db_Ddl_Table::TYPE_TEXT, 50, array(
        ), 'File Extension')
    ->addColumn('image_size_x', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned' => true,
        ), 'Image Size X')
    ->addColumn('image_size_y', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned' => true,
        ), 'Image Size Y')
    ->addColumn('sort_order', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Sort Order')
 
    ->setComment('Emipro Product Option Table');
$installer->getConnection()->createTable($table);

$table = $installer->getConnection()
    ->newTable($installer->getTable('emipro_customoptions/product_option_price'))
    ->addColumn('option_price_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Option Price ID')
    ->addColumn('option_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Option ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Store ID')
    ->addColumn('price', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(
        'nullable'  => false,
        'default'   => '0.0000',
        ), 'Price')
    ->addColumn('price_type', Varien_Db_Ddl_Table::TYPE_TEXT, 7, array(
        'nullable'  => false,
        'default'   => 'fixed',
        ), 'Price Type')
    ->addIndex(
        $installer->getIdxName(
            'emipro_customoptions/product_option_price',
            array('option_id', 'store_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('option_id', 'store_id'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
    ->addIndex($installer->getIdxName('emipro_customoptions/product_option_price', array('option_id')),
        array('option_id'))
    ->addIndex($installer->getIdxName('emipro_customoptions/product_option_price', array('store_id')),
        array('store_id'))
    ->addForeignKey(
        $installer->getFkName(
            'emipro_customoptions/product_option_price',
            'option_id',
            'emipro_customoptions/product_option',
            'option_id'
        ),
        'option_id', $installer->getTable('emipro_customoptions/product_option'), 'option_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey(
        $installer->getFkName(
            'emipro_customoptions/product_option_price',
            'store_id',
            'core/store',
            'store_id'
        ),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Emipro Product Option Price Table');
$installer->getConnection()->createTable($table);

$table = $installer->getConnection()
    ->newTable($installer->getTable('emipro_customoptions/product_option_title'))
    ->addColumn('option_title_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Option Title ID')
    ->addColumn('option_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Option ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Store ID')
    ->addColumn('title', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => true,
        'default'   => null,
        ), 'Title')
    ->addIndex(
        $installer->getIdxName(
            'emipro_customoptions/product_option_title',
            array('option_id', 'store_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('option_id', 'store_id'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
    ->addIndex($installer->getIdxName('emipro_customoptions/product_option_title', array('option_id')),
        array('option_id'))
    ->addIndex($installer->getIdxName('emipro_customoptions/product_option_title', array('store_id')),
        array('store_id'))
    ->addForeignKey(
        $installer->getFkName(
            'emipro_customoptions/product_option_title',
            'option_id',
            'emipro_customoptions/product_option',
            'option_id'
        ),
        'option_id', $installer->getTable('emipro_customoptions/product_option'), 'option_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey(
        $installer->getFkName(
            'emipro_customoptions/product_option_title',
            'store_id',
            'core/store',
            'store_id'
        ),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Emipro Product Option Title Table');
$installer->getConnection()->createTable($table);

$table = $installer->getConnection()
    ->newTable($installer->getTable('emipro_customoptions/product_option_type_value'))
    ->addColumn('option_type_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Option Type ID')
    ->addColumn('option_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Option ID')
    ->addColumn('sku', Varien_Db_Ddl_Table::TYPE_TEXT, 64, array(
        ), 'SKU')
    ->addColumn('sort_order', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Sort Order')
    ->addIndex($installer->getIdxName('emipro_customoptions/product_option_type_value', array('option_id')),
        array('option_id'))
    ->addForeignKey(
        $installer->getFkName(
            'emipro_customoptions/product_option_type_value',
            'option_id',
            'emipro_customoptions/product_option',
            'option_id'
        ),
        'option_id', $installer->getTable('emipro_customoptions/product_option'), 'option_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Catalog Product Option Type Value Table');
$installer->getConnection()->createTable($table);


$table = $installer->getConnection()
    ->newTable($installer->getTable('emipro_customoptions/product_option_type_price'))
    ->addColumn('option_type_price_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Option Type Price ID')
    ->addColumn('option_type_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Option Type ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Store ID')
    ->addColumn('price', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(
        'nullable'  => false,
        'default'   => '0.0000',
        ), 'Price')
    ->addColumn('price_type', Varien_Db_Ddl_Table::TYPE_TEXT, 7, array(
        'nullable'  => false,
        'default'   => 'fixed',
        ), 'Price Type')
    ->addIndex(
        $installer->getIdxName(
            'emipro_customoptions/product_option_type_price',
            array('option_type_id', 'store_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('option_type_id', 'store_id'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
    ->addIndex($installer->getIdxName('emipro_customoptions/product_option_type_price', array('option_type_id')),
        array('option_type_id'))
    ->addIndex($installer->getIdxName('emipro_customoptions/product_option_type_price', array('store_id')),
        array('store_id'))
    ->addForeignKey(
        $installer->getFkName(
            'emipro_customoptions/product_option_type_price',
            'option_type_id',
            'emipro_customoptions/product_option_type_value',
            'option_type_id'
        ),
        'option_type_id', $installer->getTable('emipro_customoptions/product_option_type_value'), 'option_type_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey(
        $installer->getFkName(
            'emipro_customoptions/product_option_type_price',
            'store_id',
            'core/store',
            'store_id'
        ),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Emipro Product Option Type Price Table');
$installer->getConnection()->createTable($table);


$table = $installer->getConnection()
    ->newTable($installer->getTable('emipro_customoptions/product_option_type_title'))
    ->addColumn('option_type_title_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Option Type Title ID')
    ->addColumn('option_type_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Option Type ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Store ID')
    ->addColumn('title', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => true,
        'default'   => null,
        ), 'Title')
    ->addIndex(
        $installer->getIdxName(
            'emipro_customoptions/product_option_type_title',
            array('option_type_id', 'store_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('option_type_id', 'store_id'), array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
    ->addIndex($installer->getIdxName('emipro_customoptions/product_option_type_title', array('option_type_id')),
        array('option_type_id'))
    ->addIndex($installer->getIdxName('emipro_customoptions/product_option_type_title', array('store_id')),
        array('store_id'))
    ->addForeignKey(
        $installer->getFkName(
            'emipro_customoptions/product_option_type_title',
            'option_type_id',
            'emipro_customoptions/product_option_type_value',
            'option_type_id'
        ),
        'option_type_id', $installer->getTable('emipro_customoptions/product_option_type_value'), 'option_type_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('emipro_customoptions/product_option_type_title', 'store_id', 'core/store', 'store_id'),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Catalog Product Option Type Title Table');
$installer->getConnection()->createTable($table);
 $product = Mage::getModel('catalog/product');
           $productId = $product->getIdBySku('customoptionmaster');
           $product->load($productId);
           $opt = $product->getOptions();
           $newOptionsVal = array();
           $optionsArray=array();

						foreach ($opt as $o) {
                            $optionValues = $o->getValues();			
                            $newOptions = array(); //array of custom options
                            $newOptions['title'] = $o->getTitle();
                            $newOptions['is_require'] = $o->getIsRequire();
                            $newOptions['type'] = $o->getType();
                            $newOptions['sort_order'] = $o->getSortOrder();
                            $newOptions ['sku'] = $o->getSku();
                            $newOptions ['max_characters'] = $o->getMaxCharacters();
                            $newOptions ['file_extension'] = $o->getFileExtension();
                            $newOptions ['image_size_x'] = $o->getImageSizeX();
                            $newOptions ['image_size_y'] = $o->getImageSizeY();
                            $newOptions ['default_title'] = $o->getDefaultTitle();
                            $newOptions ['store_title'] = $o->getStoreTitle();
                            $newOptions ['default_price'] = $o->getDefaultPrice();
                            $newOptions ['default_price_type'] = $o->getDefaultPriceType();
                            $newOptions ['store_price'] = $o->getStorePrice();
                            $newOptions ['store_price_type'] = $o->getStorePriceType();
                            $newOptions ['price'] = $o->getPrice();
                            $newOptions ['price_type'] = $o->getPriceType();

                            $newOptionsVal = array();
                            foreach ($optionValues as $val) {
                                $copyValues = array();
                                $newOptionsValues = array(); //array of custom options values

                                $newOptionsValues['sku'] = $val->getSku();
                                $newOptionsValues['sort_order'] = $val->getSortOrder();
                                $newOptionsValues['default_title'] = $val->getDefaultTitle();
                                $newOptionsValues['store_title'] = $val->getStoreTitle();
                                $newOptionsValues['title'] = $val->getTitle();
                                $newOptionsValues['default_price'] = $val->getDefaultPrice();
                                $newOptionsValues['default_price_type'] = $val->getDefaultPriceType();
                                $newOptionsValues['store_price'] = $val->getStorePrice();
                                $newOptionsValues['store_price_type'] = $val->getStorePriceType();
                                $newOptionsValues['price'] = $val->getPrice();
                                $newOptionsValues['price_type'] = $val->getPriceType();

                                $newOptionsVal[] = ($newOptionsValues);
                            }
                            $newOptions['values'] = $newOptionsVal;
                            Mage::getModel("emipro_customoptions/product_option")->saveOptions($newOptions);
						}




$installer->endSetup();


