<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-10-30, 14:01:42)
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
 * 
 * @method string getName()
 * @method string getActive()
 * @method string getCodePool()
 * @method string getDepends()
 */
class Modulesgarden_Base_Model_Extension extends Varien_Object {

    protected $_versionFileApplied = false;
    protected $_remoteDetailsApplied = false;

    public function getWikiUrl() {
        $this->_applyVersionFileDetails();
        return $this->getData('wiki_url');
    }

    public function getVersion() {
        $this->_applyVersionFileDetails();
        return $this->getData('version');
    }

    public function getFriendlyName() {
        $this->_applyVersionFileDetails();
        return $this->getData('friendly_name') ? $this->getData('friendly_name') : $this->getData('name');
    }

    public function getLatestVersion() {
        $this->_applyRemoteDetails();
        return $this->getData('latest_version');
    }

    public function getIconUrl($default = '') {
        $this->_applyRemoteDetails();
        return $this->getData('icon_url') ? $this->getData('icon_url') : $default;
    }

    public function getPrice() {
        $this->_applyRemoteDetails();
        return $this->getData('price');
    }

    public function getDescription() {
        $this->_applyRemoteDetails();
        return $this->getData('description');
    }

    public function getChangelogUrl() {
        $this->_applyRemoteDetails();
        return $this->getData('changelog_url');
    }

    /**
     * @todo add API response field
     * @return string
     */
    public function getBuyUrl() {
        return str_replace('changelog', 'pricing', $this->getChangelogUrl());
    }

    /**
     * @todo add API response field
     * @return string
     */
    public function getFeaturesUrl() {
        return str_replace('changelog', 'features', $this->getChangelogUrl());
    }

    public function isUpgardeAvailable() {
        return $this->getVersion() && $this->getLatestVersion() && version_compare($this->getLatestVersion(), $this->getVersion()) === 1;
    }

    public function isModulesgarden() {
        return strpos($this->getName(), 'Modulesgarden_') === 0;
    }

    public function isMage() {
        return strpos($this->getName(), 'Mage_') === 0;
    }

    public function isModulesgardenTheme() {
        return $this->isModulesgarden() AND strpos($this->getName(), 'Theme') !== false;
    }

    /**
     * Used on store, it relys on Friendly Name
     */
    public function isAlreadyInstalled() {
        $this->_applyVersionFileDetails();
        $modulesgardenExtensionsInstalled = Mage::getResourceModel('modulesgardenbase/extension')->getModulesgardenCollection();
        foreach ($modulesgardenExtensionsInstalled as $ext) {
            if (trim(strtolower(htmlspecialchars_decode($ext->getFriendlyName()))) == trim(strtolower(htmlspecialchars_decode($this->getFriendlyName())))) {
                return true;
            }
        }
        return false;
    }

    protected function _applyRemoteDetails() {
        if (!$this->_remoteDetailsApplied) {
            $this->_applyVersionFileDetails();
            Mage::getResourceModel('modulesgardenbase/extension')->applyRemoteDetails($this);
            $this->_remoteDetailsApplied = true;
        }
        return $this;
    }

    protected function _applyVersionFileDetails() {
        if (!$this->_versionFileApplied) {
            Mage::getResourceModel('modulesgardenbase/extension')->applyVersionFileDetails($this);
            $this->_versionFileApplied = true;
        }
        return $this;
    }

}
