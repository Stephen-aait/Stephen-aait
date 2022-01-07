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
    ALTER TABLE `{$this->getTable('amorderattach/field')}` ADD  `status_backend` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
ADD  `status_frontend` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;
");

$installer->endSetup(); 