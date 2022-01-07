<?php
/**
 * @author      Sharad Patel
 * @category    Sale
 * @package     Expert_Orderdelete
 * @copyright   Copyright (c) 2013 expertsofttechsolution.com 
 */
$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('orderdelete')};    
	CREATE TABLE {$this->getTable('orderdelete')} (
  `orderdelete_id` int(11) NOT NULL auto_increment,   
  `internal_company_id` varchar(80) NOT NULL default '',
  PRIMARY KEY  (`orderdelete_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup(); 