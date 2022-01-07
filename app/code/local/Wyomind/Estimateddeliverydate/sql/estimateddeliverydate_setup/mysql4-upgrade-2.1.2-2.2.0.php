<?php

$installer = $this;

$installer->getConnection()->addColumn($installer->getTable('catalog/product_option_type_value'), 'leadtime', 'VARCHAR(9) NOT NULL DEFAULT 0');
$installer->endSetup();
