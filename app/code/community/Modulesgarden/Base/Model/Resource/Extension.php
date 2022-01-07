<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-10-31, 08:37:18)
 * 
 *
 *  CREATED BY MODULESGARDEN       ->        http://modulesgarden.com
 *  CONTACT                        ->       contact@modulesgarden.com
 *
 *
 *
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 *
 *
 * ******************************************************************** */

/**
 * @author Grzegorz Draganik <grzegorz@modulesgarden.com>
 */
class Modulesgarden_Base_Model_Resource_Extension {

    protected static $_remoteResponse;

    public function getModulesgardenCollection() {
        return $this->_getCollection(true);
    }

    public function getNonModulesgardenCollection($skipMage = true) {
        return $this->_getCollection(null, true, $skipMage);
    }

    public function getNonDefaultCollection() {
        return $this->_getCollection(false, false, true);
    }

    protected function _getCollection($modulesgardenOnly = null, $nonModulesgardenOnly = null, $skipMage = false) {
        $collection = new Varien_Data_Collection();
        $modulesArray = (array) Mage::getConfig()->getNode('modules')->children();
        foreach ($modulesArray as $module => $moduleDetails) {
            $ext = Mage::getModel('modulesgardenbase/extension');
            $ext->setName($module);
            $ext->setActive((string) $moduleDetails->active == 'true');
            $ext->setCodePool((string) $moduleDetails->codePool);
            $ext->setVersion(isset($moduleDetails->version) ? (string) $moduleDetails->version : $this->_getModuleVersionFromItsXml($ext) );

            if (isset($moduleDetails->depends)) {
                $depends = array();
                foreach ($moduleDetails->depends as $dependModuleName => $dependModuleDetails) {
                    $depends[] = $dependModuleName;
                }
                $ext->setDepends($depends);
            }

            if ($modulesgardenOnly === true && !$ext->isModulesgarden())
                continue;

            if ($nonModulesgardenOnly === true && $ext->isModulesgarden())
                continue;

            if ($skipMage === true && $ext->isMage())
                continue;

            $collection->addItem($ext);
        }

        return $collection;
    }

    protected function _getModuleVersionFromItsXml(Modulesgarden_Base_Model_Extension $ext) {
        $configXmlPath = Mage::getModuleDir('', $ext->getName()) . DIRECTORY_SEPARATOR . 'etc' . DIRECTORY_SEPARATOR . 'config.xml';
        if (file_exists($configXmlPath)) {
            $fileConfig = new Mage_Core_Model_Config_Base();
            $fileConfig->loadFile($configXmlPath);
            return (string) $fileConfig->getNode('modules/' . $ext->getName() . '/version');
        }
        return null;
    }

    /**
     * @return array
     */
    public function getExtensionsFromModulesgardenCom() {
        if (self::$_remoteResponse !== null)
            return self::$_remoteResponse;

        $client = new Modulesgarden_Base_Model_Extension_Client();

        $response = $client->getAvailableProducts('MAGENTO');
        if (isset($response->status) && $response->status) {
            self::$_remoteResponse = $response->data->modules;
            return self::$_remoteResponse;
        }
        return self::$_remoteResponse = array();
    }

    public function getExtensionsObjectsFromModulesgardenCom($sortableCallback = null) {
        $extToReturn = array();
        $extensions = $this->getExtensionsFromModulesgardenCom();
        foreach ($extensions as $remoteExt) {
            $ext = Mage::getModel('modulesgardenbase/extension');
            $ext->setFriendlyName($remoteExt->name);
            $this->applyRemoteDetails($ext);
            $extToReturn[] = $ext;
        }

        if (is_callable($sortableCallback)) {
            $collection = array();

            foreach ($extToReturn as $key => $item) {
                $collection[$key] = $sortableCallback($item);
            }

            asort($collection);

            foreach (array_keys($collection) as $key) {
                $collection[$key] = $extToReturn[$key];
            }

            return $collection;
        }

        return $extToReturn;
    }

    public function applyRemoteDetails(Modulesgarden_Base_Model_Extension $ext) {
        $extensions = $this->getExtensionsFromModulesgardenCom();
        
        foreach ($extensions as $remoteExt) {
            if (trim(strtolower(htmlspecialchars_decode($ext->getFriendlyName()))) == trim(strtolower(htmlspecialchars_decode($remoteExt->name)))) {
                $ext->setChangelogUrl(isset($remoteExt->site_url) ? $remoteExt->site_url : '');
                $ext->setLatestVersion(isset($remoteExt->version) ? $remoteExt->version : '');
                $ext->setIconUrl(isset($remoteExt->icon_url) ? $remoteExt->icon_url : '');
                $ext->setPrice(isset($remoteExt->price) ? $remoteExt->price : '');
                $ext->setDescription(isset($remoteExt->description) ? $remoteExt->description : '');
                $ext->setIsPromotion(isset($remoteExt->is_promotion) && $remoteExt->is_promotion ? true : false);
                $ext->setIsNew(isset($remoteExt->is_new) && $remoteExt->is_new ? true : false);
            }
        }
    }

    public function applyVersionFileDetails(Modulesgarden_Base_Model_Extension $ext) {
        $modulePath = Mage::getModuleDir('', $ext->getName());
        if (file_exists($modulePath . DIRECTORY_SEPARATOR . 'moduleVersion.php')) {
            require $modulePath . DIRECTORY_SEPARATOR . 'moduleVersion.php';
            $ext->setFriendlyName($moduleName);
            $ext->setCurrentVersion($moduleVersion);
            $ext->setWikiUrl($moduleWikiUrl);

            // Non-standard variable
            if (isset($moduleChangelogUrl)) {
                $ext->setChangelogUrl($moduleChangelogUrl);
            }
        }
    }

}
