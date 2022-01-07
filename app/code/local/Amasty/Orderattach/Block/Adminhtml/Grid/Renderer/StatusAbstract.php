<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */
class Amasty_Orderattach_Block_Adminhtml_Grid_Renderer_StatusAbstract extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    
   public function render(Varien_Object $row)
   {
        static $statuses = array();
        if (!$statuses)
        {
            $statuses = Mage::getSingleton('sales/order_config')->getStatuses();
            $statuses['all'] = 'All';
        }
        $appliedStatuses = explode(',', $row->getData($this->getType()));
        $output = array();
       
        foreach ($appliedStatuses as $code)
        {
            if (isset($statuses[$code]))
            {
                $output[] = $statuses[$code];
            }
        }

        foreach ($statuses as $code => $name)
        {
            $values[$code] = $name;
        }

        return implode(', ', $output);
    }
}