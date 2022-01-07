<?php

class Iksanika_Productupdater_Block_Widget_Grid_Column_Filter_Multiselect 
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Select
{
    public function getCondition()
    {
        if ('null' == $this->getValue())
        {
            return array('null' => true);
        }
        return array('finset' => $this->getValue());
    }
}