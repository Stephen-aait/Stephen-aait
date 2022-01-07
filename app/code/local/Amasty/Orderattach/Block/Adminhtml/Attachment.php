<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */
class Amasty_Orderattach_Block_Adminhtml_Attachment extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'amorderattach';
        $this->_controller = 'adminhtml';
        $this->_headerText = Mage::helper('amorderattach')->__('Manage Fields');
        $this->_addButtonLabel = Mage::helper('amorderattach')->__('Add New Field');
        parent::__construct();
    }

}
