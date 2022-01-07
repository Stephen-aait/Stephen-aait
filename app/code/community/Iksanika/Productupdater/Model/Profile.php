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

class Iksanika_Productupdater_Model_Profile
{
    
    
/*    
    public $columns_redirectAdvancedProductManager = 1;
    
    public $images_showurl = 0;
    public $images_showbydefault = 1;
    public $images_width= 75;
    public $images_height= 75;
    
    public $productrelator_enablerelated = 1;
    public $productrelator_enablecrosssell = 1;
    public $productrelator_enableupsell = 1;

    public $massactions_enableaddcategory = 1;
    public $massactions_enableremovecategory = 1;
    public $massactions_categorynames = 1;
*/   
    
    // general data: start
    // 
    // * Redirect to Products Manager (Advanced)
    // * Image Width
    // * Image Height
    // * Image Scale
    // * Show Image Url
    // * Mass Actions / Category Names
    // * Mass Actions / Enable Remove Category Action
    // * Mass Actions / Enable Add Category Admin
    // * Product Relator / Enable Related
    // * Product Relator / Enable Cross-sell
    // * Product Relator / Enable Up-sell
    // 
    // general data: end
    
    public $profileId = null;
    public $profileName = 'New Profile';
    
    public $columns_showcolumns = 'id,name,type_id,attribute_set_id,sku,price,qty,visibility,status,websites,image';
    public $columns_limit = 50;
    public $columns_page = 1;
    public $columns_sort = 'id';
    public $columns_dir = 'desc';
    
    public $columns_positions = '';
    public $columns_width = '';
    
    public $columns_associatedShow = 1;
    public $stockmanage_autoStockStatus = 0;

    public function save()
    {
        Mage::getModel('core/config')->saveConfig('productupdater/profile_'.$this->profileId, serialize($this));
    }
    
    public function load($profileId = null)
    {
        return unserialize(Mage::getStoreConfig('productupdater/profile_'.($profileId != null ? $profileId : $this->profileId)));
    }
    
    public function setProfileId($profileId)
    {
        $this->profileId = $profileId;
    }
}
