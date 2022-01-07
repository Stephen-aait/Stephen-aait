<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */
class Amasty_Orderattach_Block_Adminhtml_Sales_Order_View_Attachment_Renderer_File_Multiple extends Amasty_Orderattach_Block_Adminhtml_Sales_Order_View_Attachment_Renderer_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('amorderattach/renderer/file_multiple.phtml');
    }
    
    /**
    * with multiple file we have an array of values (string, separated by ;)
    * 
    */
    public function getValue()
    {
        $values = array();
        if (parent::getValue())
        {
            $values = explode(';', trim(parent::getValue()));
        }
        return $values;
    }
}