<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */
class Amasty_Orderattach_Block_Sales_Order_View_Attachment_Renderer_Link extends Amasty_Orderattach_Block_Sales_Order_View_Attachment_Renderer_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('amorderattach/renderer/link.phtml');
    }

    public function getLink()
    {
        $value = $this->getValue();
        $value = explode('|', $value);

        return $value[0];
    }

    public function getName()
    {
        $value = $this->getValue();
        $value = explode('|', $value);

        return isset($value[1]) ? $value[1] : $value[0];
    }
}