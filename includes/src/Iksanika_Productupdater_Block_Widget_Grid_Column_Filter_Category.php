<?php


class Iksanika_Productupdater_Block_Widget_Grid_Column_Filter_Category 
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Select
{
    public function getCondition()
    {
        if(trim($this->getValue())=='')
            return null;
        $categoryIds         =   explode(',', $this->getValue());
        $categoryIdsArray    =   array();
        foreach($categoryIds as $skuId)
            $categoryIdsArray[] = trim($skuId);
        return array('inset' => $categoryIdsArray);
    }
}