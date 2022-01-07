<?php
class Emipro_Customoptions_Block_Adminhtml_Assigntoproduct_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('customoptions_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle(Mage::helper('emipro_customoptions')->__('Assign/Remove Options'));
	}
	public function _beforeToHtml()
	{
		$this->addTab('abandonedcart_grid_section', array(
			'label'     => Mage::helper('emipro_customoptions')->__('Assign/Remove Options'),
			'title'     => Mage::helper('emipro_customoptions')->__('Assign/Remove Options'),
			'content'   => $this->getLayout()->createBlock('emipro_customoptions/adminhtml_assigntoproduct_edit_tab_assign')->toHtml().$this->getLayout()->createBlock('emipro_customoptions/adminhtml_assigntoproduct_edit_tab_grid')->toHtml(),
		));
		return parent::_beforeToHtml();
	}
}
