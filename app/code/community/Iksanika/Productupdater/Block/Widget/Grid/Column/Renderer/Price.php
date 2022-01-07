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

class Iksanika_Productupdater_Block_Widget_Grid_Column_Renderer_Price 
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Price
{

    public function render(Varien_Object $row) 
    {
        $data = $row->getData($this->getColumn()->getIndex());
        $currency_code = $this->_getCurrencyCode($row);

        if (!$currency_code) 
        {
            return $data;
        }
        $data = floatval($data) * $this->_getRate($row);
        $data = sprintf("%f", $data);
        $data = Mage::app()->getLocale()->currency($currency_code)->toCurrency($data, array('display' => Zend_Currency::NO_SYMBOL));
        return '<input type="text" name="'.$this->getColumn()->getIndex().'" value="'.(($data !=0)? $data : '').'" class="input-text ">';
    }
}
    