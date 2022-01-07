<?php
$installer = $this;
$installer->startSetup();
$installer->getConnection()->addColumn($this->getTable('designersoftware/designersoftware'),
    'remove_status',      	//column name
    'smallint(6) NOT NULL DEFAULT 0'  //datatype definition
);
$installer->endSetup();
