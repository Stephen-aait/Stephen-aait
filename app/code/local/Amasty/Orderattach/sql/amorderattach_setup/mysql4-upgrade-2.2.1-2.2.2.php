<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */

$this->startSetup();

$this->run("
    UPDATE `{$this->getTable('amorderattach/field')}` SET `code`=`fieldname`;

    ALTER TABLE `{$this->getTable('amorderattach/field')}` DROP `fieldname`;
");

$this->endSetup();
