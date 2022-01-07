<?php
$installer = $this;
$installer->startSetup();
$installer->getConnection()->addColumn($this->getTable('designersoftware/designersoftware'),
    'category_id',      	//column name
    'varchar(255) NOT NULL'  //datatype definition
);
$installer->endSetup();
