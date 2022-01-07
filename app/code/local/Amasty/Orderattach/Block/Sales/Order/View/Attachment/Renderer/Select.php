<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */
class Amasty_Orderattach_Block_Sales_Order_View_Attachment_Renderer_Select extends Amasty_Orderattach_Block_Sales_Order_View_Attachment_Renderer_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('amorderattach/renderer/select.phtml');
    }
    
    public function getOptions()
    {
        $options = explode(',', $this->getAttachmentField()->getOptions());
        if (is_array($options))
        {
            $options = array_map('trim', $options);
        }
        return $options;
    }
}