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
    ALTER TABLE `{$this->getTable('amorderattach/field')}` ADD `customer_visibility` ENUM( 'no', 'view', 'edit' ) NOT NULL ;
");

$installer->run("
    ALTER TABLE `{$this->getTable('amorderattach/field')}` ADD INDEX ( `customer_visibility` ) ;
");

$installer->endSetup(); 