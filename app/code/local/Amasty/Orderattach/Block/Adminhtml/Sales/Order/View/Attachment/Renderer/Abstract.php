<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */
abstract class Amasty_Orderattach_Block_Adminhtml_Sales_Order_View_Attachment_Renderer_Abstract extends Mage_Adminhtml_Block_Template
{
    protected $_attachmentFieldModel;
    
    public function render()
    {
        return $this->toHtml();
    }
    
    public function setAttachmentField(Amasty_Orderattach_Model_Field $model)
    {
        $this->_attachmentFieldModel = $model;
    }
    
    public function getAttachmentField()
    {
        return $this->_attachmentFieldModel;
    }
    
    public function getValue()
    {
        return Mage::registry('current_attachment_order_field')->getData($this->getAttachmentField()->getCode());
    }
}