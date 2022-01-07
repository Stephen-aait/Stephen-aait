<?php
$installer = $this;
$installer->startSetup();
$installer->run("

--
-- Creating Structure for table `inky_model`
--

-- DROP TABLE IF EXISTS {$this->getTable('inky_category')};
CREATE TABLE {$this->getTable('inky_category')} (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `status` smallint(6) NOT NULL DEFAULT '0',
  `created_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

");

$installer->endSetup(); 
