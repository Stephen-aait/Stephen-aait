<?php

$installer = $this;

$installer->startSetup();
$setup = new Mage_Sales_Model_Resource_Setup('core_setup');


$installer->getConnection()->addColumn($installer->getTable('sales_flat_quote'),  'estimated_delivery_date', 'TEXT');
$setup->addAttribute('quote', 'estimated_delivery_date', array('type' => 'static', 'visible' => false));
   
$installer->getConnection()->addColumn($installer->getTable('sales_flat_order'), 'estimated_delivery_date', 'TEXT');
$setup->addAttribute('order', 'estimated_delivery_date', array('type' => 'static', 'visible' => false));

$installer->endSetup();