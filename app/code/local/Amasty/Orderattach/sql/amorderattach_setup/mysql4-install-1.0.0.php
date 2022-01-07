<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("
CREATE TABLE IF NOT EXISTS `{$this->getTable('amorderattach/field')}` (
  `field_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `code` VARCHAR( 128 ) NOT NULL ,
  `fieldname` VARCHAR( 128 ) NOT NULL,
  `label` VARCHAR( 255 ) NOT NULL ,
  `type` VARCHAR( 16 ) NOT NULL ,
  `show_on_grid` TINYINT( 1 ) UNSIGNED NOT NULL ,
  `is_enabled` TINYINT( 1 ) UNSIGNED NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET=utf8 ;
");

$installer->run("
CREATE TABLE IF NOT EXISTS `{$this->getTable('amorderattach/order_field')}` (
  `entity_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`entity_id`),
  UNIQUE KEY `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;
");


$installer->endSetup(); 