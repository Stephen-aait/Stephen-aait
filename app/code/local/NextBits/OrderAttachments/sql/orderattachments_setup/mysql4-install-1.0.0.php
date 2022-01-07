<?php 
$installer = $this;
$installer->startSetup();
$installer->run("
DROP TABLE IF EXISTS {$this->getTable('nboa_order_attachments')};
CREATE TABLE {$this->getTable('nboa_order_attachments')} (
	`order_attachments_id` int(11) unsigned NOT NULL auto_increment,
	`order_id` int(11) NOT NULL,
	`file` varchar(255),
	`comment` text,
	`visible_customer_account` tinyint(1),
	`created_on` datetime,
	`updated_on` datetime,
	`customer_id` int(11),
	PRIMARY KEY (`order_attachments_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;	
");
$installer->endSetup();
?>