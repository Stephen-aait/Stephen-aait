<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */
class Amasty_Orderattach_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getTypes($hash = true)
    {
        $types = array(
            'string'        => Mage::helper('amorderattach')->__('Text Field'),
            'text'          => Mage::helper('amorderattach')->__('Text Area'),
            'file'          => Mage::helper('amorderattach')->__('Single File Upload'),
            'file_multiple' => Mage::helper('amorderattach')->__('Multiple Files Upload'),
            'date'          => Mage::helper('amorderattach')->__('Date'),
            'select'        => Mage::helper('amorderattach')->__('Dropdown'),
            'link'          => Mage::helper('amorderattach')->__('Link'),
        );
        if ($hash)
        {
            return $types;
        }
        $typesValLabel = array();
        foreach ($types as $key => $val)
        {
            $typesValLabel[] = array(
                'value' => $key,
                'label' => $val,
            );
        }
        return $typesValLabel;
    }
    
    public function getCustomerVisibility($asHash = false)
    {
        $options = array(
            array(
                'value' => 'no',
                'label' => $this->__('Do Not Display'),
            ),
            array(
                'value' => 'view',
                'label' => $this->__('View Only'),
            ),
            array(
                'value' => 'edit',
                'label' => $this->__('Allow To Edit'),
            ),
        );
        if ($asHash)
        {
            $hash = array();
            foreach ($options as $option)
            {
                $hash[$option['value']] = $option['label'];
            }
            return $hash;
        }
        return $options;
    }

    /**
     * @deprecated after 2.4.10
     * @return string
     */
    public function getViewDateFormat()
    {
        return 'M d, Y';
    }

    public function getRenderedDate($date)
    {
        $date = str_replace('/', '-', $date); //avoid "unsupported ISO8601 format" exception
        $format =Mage::app()->getLocale()->getDateFormat(
            Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);
        $date = Mage::getSingleton('core/locale')
            ->date($date, Zend_Date::ISO_8601, null, false)
            ->toString($format);
        return $date;
    }
    
    public function isInSetStatus($status,$arrayStatus)
    {
        $arrayStatus = explode(",",$arrayStatus);
        foreach($arrayStatus as $key=>$value){
            if ("all" == $value){
                return true;
            }
            if ($status == $value){
                return true;
            }
        }
        return false;
    }
    
    public function clearCache()
    {
        $cacheDir = Mage::getBaseDir('var') . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR;
        $this->_clearDir($cacheDir);
        Mage::app()->cleanCache();
        Mage::getConfig()->reinit();
    }
    
    protected function _clearDir($dir = '')
    {
        if($dir) 
        {
            if (is_dir($dir)) 
            {
                if ($handle = @opendir($dir)) 
                {
                    while (($file = readdir($handle)) !== false) 
                    {
                        if ($file != "." && $file != "..") 
                        {
                            $fullpath = $dir . '/' . $file;
                            if (is_dir($fullpath)) 
                            {
                                $this->_clearDir($fullpath);
                                @rmdir($fullpath);
                            }
                            else 
                            {
                                @unlink($fullpath);
                            }
                        }
                    }
                    closedir($handle);
                }
            }
        }
    }

    public function getColumnsProperties($json = true, $reloadAttributes = false)
    {
        $prop = array();


//        if (Mage::getStoreConfig('ampgrid/attr/cols'))
        {

            $attributes = Mage::getModel('amorderattach/field')->getCollection();
            $attributes->addFieldToFilter('show_on_grid', 1);


            if ($attributes)
            {
                foreach ($attributes as $attribute)
                {
                    $prop[$attribute->getCode()] = array(
                        'col'       => $attribute->getCode(),
                        'type'      => $attribute->getType(),
                    );
                    if ('select' == $attribute->getType())
                    {
                        $propOptions = explode(',', $attribute->getOptions());

                        $prop[$attribute->getCode()]['options'] = $propOptions;

                        if (!$propOptions)
                        {
                            unset($prop[$attribute->getCode()]); // we should not make attribute editable, if it has no options
                        }
                    }
                }
            }
        }

        if (!$json)
        {
            return $prop;
        }

        return Mage::helper('core')->jsonEncode($prop);
    }

    public function isAllowedEdit()
    {
        return Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/edit_memos');
    }
}