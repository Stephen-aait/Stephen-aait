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

class Iksanika_Productupdater_Helper_Data extends Mage_Core_Helper_Abstract 
{
    
    protected static $showImageBase = null;
    protected static $showImageSmall = null;
    protected static $showImageThumbnail = null;
    
    public static function initSettings()
    {
        self::$showImageBase        =   (!self::$showImageBase) ? ((int)Mage::getStoreConfig('productimages/images/image_base') === 1) : null;
        self::$showImageSmall       =   (!self::$showImageSmall) ? ((int)Mage::getStoreConfig('productimages/images/image_small') === 1) : null;
        self::$showImageThumbnail   =   (!self::$showImageThumbnail) ? (int)Mage::getStoreConfig('productimages/images/image_thumbnail') === 1 : null;
    }
    
    public function showImageBase()
    {
        self::initSettings();
        return self::$showImageBase;
    }
    
    public function showImageSmall()
    {
        self::initSettings();
        return self::$showImageSmall;
    }
    
    public function showImageThumbnail()
    {
        self::initSettings();
        return self::$showImageThumbnail;
    }
    
    public function getImageUrl($image_file)
    {
        $url = false;
        $url = Mage::getBaseUrl('media').'catalog/product'.$image_file;
        return $url;
    }
    
    public function getFileExists($image_file)
    {
        $file_exists = false;
        $file_exists = file_exists('media/catalog/product'. $image_file);
        return $file_exists;
    }
    
    public function getStoreId()
    {
        return (int) Mage::app()->getRequest()->getParam('store', 0);
    }
    
    public function getStore()
    {
        return Mage::app()->getStore($this->getStoreId());
    }
    
    
    
    
    // get list of all products attributes
    public function getAttributesList()
    {
        $attributes = Mage::getResourceModel('catalog/product_attribute_collection')
                         ->addVisibleFilter()
                         ->addStoreLabel(Mage::helper('productupdater')->getStoreId());
        $attributes->getSelect();
        return $attributes;
    }
    
    
    public function getGridAttributes()
    {
        $selected = (string) Mage::getStoreConfig('productupdater/attributes/positions' . Mage::getSingleton('admin/session')->getUser()->getId());
        return ($selected) ? explode(',', $selected) : array();
    }
    
    public function getSelectedAttributes()
    {
        return $this->getGridAttributes();
    }
    
    public function getDelimiter()
    {
        $formattedPrice = Mage::app()->getLocale()->currency(null)->toCurrency(1);
        return strpos($formattedPrice, '.') ? '.' : ',';
    }
    
    public function recalculatePrice($originalPrice, $newPrice)
    {
//        $delimiter  =   '.'; // ,
        $delimiter  =   $this->getDelimiter();
        $newPrice   =   str_replace($delimiter == '.' ? ',' : '.', '', trim($newPrice));

//        if (!preg_match('/^[0-9]+(\.[0-9]+)?$/', $newPrice))
        if (!preg_match('/^[0-9]+(\\'.$delimiter.'[0-9]+)?$/', $newPrice))
        {
//            if (!preg_match('/^[+-][0-9]+(\.[0-9]+)?%?$/', $newPrice))
            if (!preg_match('/^[+-][0-9]+(\\'.$delimiter.'[0-9]+)?%?$/', $newPrice))
            {
                throw new Exception(Mage::helper('productupdater')->__('Please provide the difference as +5'.$delimiter.'25 -5'.$delimiter.'25 +5'.$delimiter.'25% or -5'.$delimiter.'25%')); 
            }else
            {
                $sign       =   substr($newPrice, 0, 1);
                $newPrice   =   substr($newPrice, 1);
                $percent    =   (substr($newPrice, -1, 1) == '%');
                
                if ($percent)
                    $newPrice = substr($newPrice, 0, -1);

                $newPrice = ($delimiter == ',' ? str_replace(',', '.', $newPrice) : floatval($newPrice));
            
                if ($newPrice < 0.00001)
                {
                    throw new Exception(Mage::helper('productupdater')->__('Please provide a non empty difference'));            
                }

                $value = $percent ? ($originalPrice * $newPrice / 100) : $newPrice;

                if($sign == '+')
                {
                    $value = $originalPrice + $value;
                }else
                if($sign == '-')
                {
                    $value = $originalPrice - $value;
                }
                return $value;
            }
        }else
            return $newPrice;
    }
        
    public function isVersionNew($current, $new)
    {
        $isNew = false;
        
        $cur = explode('.', $current);
        $new = explode('.', $new);
        
        if((int)$new[0] > (int)$cur[0])
        {
            $isNew = true;
        }
        
        if((int)$new[1] > (int)$cur[1])
        {
            $isNew = true;
        }
        
        if((int)$new[2] > (int)$cur[2])
        {
            $isNew = true;
        }
        
        return $isNew;
    }

    /**
     * Convert dates in array from localized to internal format
     *
     * @param   array $dateFields
     * @return  array
     */
    public function filterDate($dateField)
    {
        if (empty($dateField)) {
            return false;
        }
        $filterInput = new Zend_Filter_LocalizedToNormalized(array(
            'date_format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT)
        ));
        $filterInternal = new Zend_Filter_NormalizedToLocalized(array(
            'date_format' => Varien_Date::DATE_INTERNAL_FORMAT
        ));

        if(!empty($dateField)) {
            $dateField = $filterInput->filter($dateField);
            $dateField = $filterInternal->filter($dateField);
        }
        return $dateField;
    }


}
