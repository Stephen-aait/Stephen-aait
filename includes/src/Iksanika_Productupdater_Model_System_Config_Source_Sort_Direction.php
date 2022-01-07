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

class Iksanika_Productupdater_Model_System_Config_Source_Sort_Direction
{
    
    const DESC_OPTION   =   'desc';
    const ASC_OPTION    =   'asc';
    
    public function toOptionArray()
    {
        $sortingOptions = array(
            array(
                'value' => Iksanika_Productupdater_Model_System_Config_Source_Sort_Direction::DESC_OPTION,   
                'label' => __('Descending')
            ),
            array(
                'value' => Iksanika_Productupdater_Model_System_Config_Source_Sort_Direction::ASC_OPTION,   
                'label' => __('Ascending')
            )
        );
        return $sortingOptions;
    }
}