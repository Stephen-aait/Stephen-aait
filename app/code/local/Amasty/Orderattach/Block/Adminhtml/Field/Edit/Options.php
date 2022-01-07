<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */
class Amasty_Orderattach_Block_Adminhtml_Field_Edit_Options extends Mage_Adminhtml_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('amorderattach/options.phtml');
    }
    
    public function getOptions()
    {
        $fieldModel = $this->getField();
        $options    = array();
        if ($fieldModel->getOptions())
        {
            $options = explode(',', $fieldModel->getOptions());
            $options = array_map('trim', $options);
        }
        return $options;
    }
}