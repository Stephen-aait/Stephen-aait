<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */
class Amasty_Orderattach_Block_Sales_Order_View_Attachment_Renderer_Date extends Amasty_Orderattach_Block_Sales_Order_View_Attachment_Renderer_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('amorderattach/renderer/date.phtml');
    }
    
    public function getValue()
    {
        $value = parent::getValue();
        if ('0000-00-00' == $value)
        {
            $value = '';
        }
        return $value;
    }
}