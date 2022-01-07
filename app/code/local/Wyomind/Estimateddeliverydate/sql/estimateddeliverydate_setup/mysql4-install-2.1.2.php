<?php

$installer = $this;
$installer->installEntities();


$installer->run("
DROP TABLE IF EXISTS {$this->getTable('eav_attribute_leadtime')};
 ");


$installer->run("
CREATE TABLE {$this->getTable('eav_attribute_leadtime')}(
  `attribute_id` smallint(5) unsigned NOT NULL  ,
  `value_id` int(10) unsigned NOT NULL ,
  `value` int(10) NOT NULL default '0',
   PRIMARY KEY  (`value_id`,`attribute_id`),
   UNIQUE KEY `UNIQ product_place` (`value_id`,`attribute_id`),
   KEY `CONST_value_id` (`value_id`),
   KEY `CONST_attribute_id` (`attribute_id`),
   CONSTRAINT `LEADTIME_ATTR_ID_CONST` FOREIGN KEY (`attribute_id`) REFERENCES `{$this->getTable('eav_attribute')}` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
   CONSTRAINT `LEADTIME_OPT_ID_CONST` FOREIGN KEY (`value_id`) REFERENCES `{$this->getTable('eav_attribute_option')}` (`option_id`) ON DELETE CASCADE  ON UPDATE CASCADE


) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;
");
   
$setup = new Mage_Sales_Model_Resource_Setup('core_setup');

$installer->getConnection()->addColumn($installer->getTable('sales_flat_quote'),  'estimated_delivery_date', 'TEXT');
$setup->addAttribute('quote', 'estimated_delivery_date', array('type' => 'static', 'visible' => false));
   
$installer->getConnection()->addColumn($installer->getTable('sales_flat_order'), 'estimated_delivery_date', 'TEXT');
$setup->addAttribute('order', 'estimated_delivery_date', array('type' => 'static', 'visible' => false));

$installer->endSetup();




 
 


