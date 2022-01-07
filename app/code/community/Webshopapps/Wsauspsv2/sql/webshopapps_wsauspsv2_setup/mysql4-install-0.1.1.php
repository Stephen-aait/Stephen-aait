<?php

$installer = $this;

$installer->startSetup();

$allowedMethods = $installer->getConnection()->fetchAll("select * from {$this->getTable('core_config_data')} where path in ('carriers/usps/allowed_methods')");

foreach ($allowedMethods as $aMethod) {
    $scopeMethods = $aMethod['value'];
    $scopeMethodsArr = explode(',', $scopeMethods);
    if(in_array('Standard Post', $scopeMethodsArr)) {
        $scopeMethods .= ',USPS Retail Ground';
        $aMethod['value'] = $scopeMethods;
        $installer->getConnection()->update($this->getTable('core_config_data'), $aMethod, 'config_id=' . $aMethod['config_id']);
    }
}

$installer->endSetup();


