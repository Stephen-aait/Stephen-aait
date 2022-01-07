<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */

$this->startSetup();

$this->run(''."

ALTER TABLE `{$this->getTable('amorderattach/field')}` ADD  `sort_order` INT( 11 ) UNSIGNED NOT NULL ;

");

$this->endSetup();
