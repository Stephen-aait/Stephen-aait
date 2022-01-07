<?php
class Emipro_Customoptions_Block_Adminhtml_Removecustomoptions_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs{
		public function __construct()
		{
				parent::__construct();
				$this->setId('removecustomoptions_tabs');
				$this->setDestElementId('edit_form');
				$this->setTitle(Mage::helper('emipro_customoptions')->__('Custom options manager'));
		}
		protected function _beforeToHtml()
		{ 

				$this->addTab('product_sku_remove_section', array(
				'label' => Mage::helper('emipro_customoptions')->__('Remove from Product (SKU list)'),
				'title' => Mage::helper('emipro_customoptions')->__('Remove from Product (SKU list)'),
				'content' => $this->getLayout()->createBlock('emipro_customoptions/adminhtml_removecustomoptions_edit_tab_removesku')->toHtml(),
				));				
				return parent::_beforeToHtml();
		}

}
