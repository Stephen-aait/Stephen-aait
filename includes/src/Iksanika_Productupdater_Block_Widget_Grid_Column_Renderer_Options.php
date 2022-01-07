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

class Iksanika_Productupdater_Block_Widget_Grid_Column_Renderer_Options
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Options
{
    public function render(Varien_Object $row)
    {
        // copied from standard Renderer_Select class
        $name = $this->getColumn()->getName() ? $this->getColumn()->getName() : $this->getColumn()->getId();
        $html = '<select name="' . $this->escapeHtml($name) . '" ' . $this->getColumn()->getValidateClass() . '>';
        $value = $row->getData($this->getColumn()->getIndex());
        $optionsInitial = $this->getColumn()->getOptions();
        
        if(($this->getColumn()->getData('attribute') && !$this->getColumn()->getData('attribute')->getIsRequired()) ||
            ($this->getColumn()->getData('attribute') && $this->getColumn()->getData('attribute')->getIsRequired() && is_null($value)))
        {
            $optionsInitialRow  =   array('' => '');
            foreach($optionsInitial as $k => $v)
            {
                $optionsInitialRow[$k] = $v;
            }
            $options = $optionsInitialRow;
        }else
        {
            $options = $optionsInitial;
        }
        
        foreach($options as $val => $label)
        {
            $selected = ( ($val == $value && (!is_null($value))) ? ' selected="selected"' : '' );
            $html .= '<option value="' . $this->escapeHtml($val) . '"' . $selected . '>';
            $html .= $this->escapeHtml($label) . '</option>';
        }
        $html.='</select>';
        return $html;
        /* initial version
        $options = $this->getColumn()->getOptions();
        if (!empty($options) && is_array($options)) 
        {
            $value = $row->getData($this->getColumn()->getIndex());
            $out = '<select name="'.$this->getColumn()->getIndex().'">';
            foreach($options as $itemId => $item)
            {
                $out .= '<option value="'.$itemId.'" '.($value == $itemId ? 'selected':'').'>'.$this->escapeHtml($item).'</option>'; 
            }
            $out .= '</select>';
            return $out;
        } */
    }
}
