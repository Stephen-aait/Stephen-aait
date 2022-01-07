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

class Iksanika_Productupdater_Block_Widget_Grid_Column_Renderer_Datepicker 
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    
    public function render(Varien_Object $row) 
    {
        $value = $row->getData($this->getColumn()->getIndex());
        $date = strtotime($value);
  
        $format = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
        if(trim($value) != '')
        {
            try {
                $value = Mage::app()->getLocale()->date($value, Varien_Date::DATETIME_INTERNAL_FORMAT)->toString($format);
            }catch (Exception $e)
            {
                $value = Mage::app()->getLocale()->date($value, Varien_Date::DATETIME_INTERNAL_FORMAT)->toString($format);
            }
        }
        
        
//        $output  = '<input type="text" name="'.$this->getColumn()->getIndex().'" id="'.$this->getColumn()->getIndex().$row->getData('entity_id').'" value="'.(($value) ? date('d.m.Y',$date) : '').'" style="width:'.($this->getColumn()->getWidth()-7).'px" class="input-text no-changes"/>';
        $output  = '<input type="text" name="'.$this->getColumn()->getIndex().'" id="'.$this->getColumn()->getIndex().$row->getData('entity_id').'" value="'.(($value) ? $value : '').'" style="width:'.($this->getColumn()->getWidth()-7).'px" class="input-text no-changes"/>';
        $output .= '<img src="'.$this->getSkinUrl('images/grid-cal.gif').'" style="padding-left: 5px;" alt="" class="v-middle" id="'.$this->getColumn()->getIndex().$row->getData('entity_id').'_trig" title="Выбор даты">';

        $format = Mage::app()->getLocale()->getDateStrFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
//            ifFormat : "%d.%m.%Y",
$str = <<<EOF
    <script type="text/javascript">
        Calendar.setup({
            inputField : "{$this->getColumn()->getIndex()}{$row->getData('entity_id')}",
            ifFormat : "{$format}",
            button : "{$this->getColumn()->getIndex()}{$row->getData('entity_id')}_trig",
            align : "Bl",
            singleClick : true
        });

//        $("{$this->getColumn()->getIndex()}{$row->getData('entity_id')}_trig").observe("click", showCalendar);

    </script>
EOF;

        return $output.$str;

    }
}
       
    
    