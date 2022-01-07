<?php 
class Emipro_Customoptions_Block_Adminhtml_Assigntoproduct_Edit_Tab_Grid extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    public function getTabLabel()
    {
        return Mage::helper('emipro_customoptions')->__('List');
    }
    public function getTabTitle()
    {
        return Mage::helper('emipro_customoptions')->__('List');
    }
    public function canShowTab()
    {
        return true;
    }
    public function isHidden()
    {
        return false;
    }
	public function _toHtml()
    {
        $this->setTemplate('customoptions/assigntoproduct.phtml');
		return parent::_toHtml();
    }
}
