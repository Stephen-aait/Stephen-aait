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

class Iksanika_Productupdater_Block_Widget_Grid_Column_Filter_Sku
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Text
{
    function getCondition()
    {
        if(trim($this->getValue())=='')
            return null;
        $skuIds = explode(',', $this->getValue());
        $skuIdsArray = array();
        foreach($skuIds as $skuId)
            $skuIdsArray[] = trim($skuId);
        if(count($skuIdsArray) == 1)
        {
            $helper = Mage::getResourceHelper('core');
            $likeExpression = $helper->addLikeEscape($this->getValue(), array('position' => 'any'));
            return array('like' => $likeExpression);
        }
        else
        {
            /* // LIKE search
            $result = array();
            foreach($skuIdsArray as $k => $v)
            {
                $helper = Mage::getResourceHelper('core');
                $likeExpression = $helper->addLikeEscape($v, array('position' => 'any'));
                $result[] = array('like' => $likeExpression);
            }
            return $result;
            */
            // exact search
            return array('inset' => $skuIdsArray);
        }
    }
}
