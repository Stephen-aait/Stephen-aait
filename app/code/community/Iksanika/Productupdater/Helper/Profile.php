<?php

/**
 * Iksanika llc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.iksanika.com/products/IKS-LICENSE.txt
 *
 * @category   Iksanika
 * @package    Iksanika_Productupdater
 * @copyright  Copyright (c) 2013 Iksanika llc. (http://www.iksanika.com)
 * @license    http://www.iksanika.com/products/IKS-LICENSE.txt
 */

class Iksanika_Productupdater_Helper_Profile 
    extends Mage_Core_Helper_Abstract
{
    
    public function generateNewProfile()
    {
        $config = Mage::getModel('core/config');
        // get ids string from config
//        $profile_ids = '0,1,2,3';
//        $profile_ids = '0';
        $profileIds = explode(',', Mage::getStoreConfig('productupdater/profile_ids'));
        
        // get latest id
        $latestProfileId = $profileIds[count($profileIds)-1];
        
        // generate new id
        $newProfileId = $latestProfileId+1;
        
        // save profile with this id in config
        $newProfile = Mage::getModel('productupdater/profile');
        $newProfile->profileId = $newProfileId;
        $config->saveConfig('productupdater/profile_'.$newProfileId, serialize($newProfile));
        
        // generate and save new ids config
        $profileIds[] = $newProfileId;
        $config->saveConfig('productupdater/profile_ids', implode(',', $profileIds));
        
        return $newProfile; // return $newProfileId;
    }
    
    public function getProfileIds()
    {
        return explode(',', Mage::getStoreConfig('productupdater/profile_ids'));
    }
    
    public function initiateProfile()
    {
        $config = Mage::getModel('core/config');
        $profile = Mage::getModel('productupdater/profile');
        $profile->profileId = 0;
        $config->saveConfig('productupdater/profile_0', serialize($profile));
    }
            
    
    public function getCollection()
    {
        $profilesIds = explode(',', Mage::getStoreConfig('productupdater/profile_ids'));
        $collection = array();
        foreach($profilesIds as $profileId)
        {
            $collection[] = unserialize(Mage::getStoreConfig('productupdater/profile_'.$profileId));
        }
        return $collection;
    }
    
    public function setCurrentProfileId($profileId = 0)
    {
        return Mage::getModel('core/config')->saveConfig('productupdater/currentProfile', $profileId);
    }
    
    public function getCurrentProfileId()
    {
        return Mage::app()->getRequest()->getParam('profile_id')
                ? Mage::app()->getRequest()->getParam('profile_id')
                : Mage::getStoreConfig('productupdater/currentProfile');
    }
    
    public function getCurrentProfile()
    {
        return unserialize(Mage::getStoreConfig('productupdater/profile_'.$this->getCurrentProfileId()));
    }
    
    public function removeProfile($profileId)
    {
        $config = Mage::getModel('core/config');
        // get ids string from config
        $profileIds = explode(',', Mage::getStoreConfig('productupdater/profile_ids'));
        $profileIdsFlipped = array_flip($profileIds);
        unset($profileIdsFlipped[$profileId]);
        $profileIds = array_flip($profileIdsFlipped);
        $config->saveConfig('productupdater/profile_ids', implode(',', $profileIds));
        $config->deleteConfig('productupdater/profile_'.$profileId);
        return true;
    }
    
    
}