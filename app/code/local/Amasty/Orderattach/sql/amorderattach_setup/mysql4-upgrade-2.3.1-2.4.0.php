<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */

$this->startSetup();

$this->run(''."

ALTER TABLE `{$this->getTable('amorderattach/field')}` ADD  `apply_to_each_product` TINYINT( 1 ) UNSIGNED NOT NULL ;

CREATE TABLE IF NOT EXISTS `{$this->getTable('amorderattach/order_products')}` (
  `entity_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_item_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`entity_id`),
  KEY `order_item_id` (`order_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

");

$this->endSetup();
