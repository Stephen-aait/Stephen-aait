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

class Iksanika_Productupdater_Block_Widget_Grid_Column_Renderer_Image 
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    protected static $showImagesUrl = null;
    protected static $showByDefault = null;
    
    protected static $imagesWidth = null;
    protected static $imagesHeight = null;
    protected static $imagesScale = null;
    
    /**
     * Renders grid column
     *
     * @param   Varien_Object $row
     * @return  string
     */
    public function render(Varien_Object $row)
    {
        return $this->_getValue($row);
    }
    
    protected static function initSettings()
    {
        if(!self::$showImagesUrl)
            self::$showImagesUrl = (int)Mage::getStoreConfig('productupdater/images/showurl') === 1;
        if(!self::$showByDefault)
            self::$showByDefault = (int)Mage::getStoreConfig('productupdater/images/showbydefault') === 1;
        if(!self::$imagesWidth)
            self::$imagesWidth = Mage::getStoreConfig('productupdater/images/width');
        if(!self::$imagesHeight)
            self::$imagesHeight = Mage::getStoreConfig('productupdater/images/height');
        if(!self::$imagesScale)
            self::$imagesScale = Mage::getStoreConfig('productupdater/images/scale');
    }
    
    
    
    protected function _getValue(Varien_Object $row)
    {
        self::initSettings();
        
        $noSelection    =   false;
        $dored          =   false;
        if ($getter = $this->getColumn()->getGetter())
        {
            $val = $row->$getter();
        }
        
        $val = $val2 = $row->getData($this->getColumn()->getIndex());
//        var_dump($val);
        $noSelection = ($val == 'no_selection' || $val == '' || $val == '/') ? true : $noSelection;
        $url = Mage::helper('productupdater')->getImageUrl($val);
        
        if(!Mage::helper('productupdater')->getFileExists($val)) 
        {
          $dored = true;
          $val .= "[*]";
        }
        
        $dored = (strpos($val, "placeholder/")) ? true : $dored;
        $filename = (!self::$showImagesUrl) ? '' : substr($val2, strrpos($val2, "/")+1, strlen($val2)-strrpos($val2, "/")-1);
        
        $val = ($dored) ? 
                ("<span style=\"color:red\" id=\"img\">$filename</span>") :
                "<span>". $filename ."</span>";
        
        $out = (!$noSelection) ? 
                ($val. '<center><a href="#" onclick="window.open(\''. $url .'\', \''. $val2 .'\')" title="'. $val2 .'" '. ' url="'.$url.'" id="imageurl">') :
                '';

        $outImagesWidth = self::$imagesWidth ? "width='".self::$imagesWidth."'":'';
        if(self::$imagesScale)
            $outImagesHeight = (self::$imagesHeight) ? "height='".self::$imagesHeight."'":'';
        else
            $outImagesHeight = (self::$imagesHeight && !self::$imagesWidth) ? "height='".self::$imagesHeight."'":'';

        //return '<img src="'.(Mage::helper("catalog/image")->init($productItem, "small_image")->resize(self::$imagesWidth)).'" '.$outImagesWidth.' '.$outImagesHeight.' alt="" />';
        try {

            $img  = Mage::helper("catalog/image")->init($row, $this->getColumn()->getIndex());
            $imgR = $img->resize(self::$imagesWidth);
//            $out .= 'select? '.(int)$noSelection;
            $out .= (!$noSelection) ?
                    "<img src=".$imgR." ".$outImagesWidth." ".$outImagesHeight." border=\"0\"/>" :
                    "<center><strong>[".__('NO IMAGE')."]</strong></center>";
        }catch(Exception $e)
        {
            $out .= "<center><strong>[".__('NO IMAGE')."]</strong></center>";
        }
        
        return $out. ((!$noSelection)? '</a></center>' : '');
    }
    
    
/*    version before 1.1.0
    protected function _getValue(Varien_Object $row)
    {
        self::initSettings();
        
        $noSelection = false;
        $dored = false;
        if ($getter = $this->getColumn()->getGetter())
        {
            $val = $row->$getter();
        }

        $val = $val2 = $row->getData($this->getColumn()->getIndex());
        if($val == 'no_selection' || $val == '')
        {
            $noSelection = true;
        }
        $url = Mage::helper('productupdater')->getImageUrl($val);
        
        if(!Mage::helper('productupdater')->getFileExists($val)) 
        {
          $dored = true;
          $val .= "[*]";
        }
        
        if(strpos($val, "placeholder/"))
        {
          $dored = true;
        }
        
        $filename = substr($val2, strrpos($val2, "/")+1, strlen($val2)-strrpos($val2, "/")-1);
        if(!self::$showImagesUrl)
        {
            $filename = '';
        }
        
        if($dored) 
        {
            $val = "<span style=\"color:red\" id=\"img\">$filename</span>";
        } else {
            $val = "<span>". $filename ."</span>";
        }
        
        $out = '';
        if(!$noSelection)
            $out = $val. '<center><a href="#" onclick="window.open(\''. $url .'\', \''. $val2 .'\')"'.
            'title="'. $val2 .'" '. ' url="'.$url.'" id="imageurl">';

        $outImagesWidth = self::$imagesWidth ? "width='".self::$imagesWidth."'":'';
        if(self::$imagesScale)
            $outImagesHeight = (self::$imagesHeight) ? "height='".self::$imagesHeight."'":'';
        else
            $outImagesHeight = (self::$imagesHeight && !self::$imagesWidth) ? "height='".self::$imagesHeight."'":'';

        if(!$noSelection)
            $out .= "<img src=".$url." ".$outImagesWidth." ".$outImagesHeight." border=\"0\"/>";
        else
            $out .= "<center><strong>[".__('NO IMAGE')."]</strong></center>";
        
        
        return $out. ((!$noSelection)? '</a></center>' : '');
    }

*/
}
