<?php
class Emipro_Customoptions_Block_Adminhtml_Manageoptions_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId('emipro_customoptions_tabs');
				$this->setDestElementId('edit_form');
				$this->setTitle(Mage::helper('emipro_customoptions')->__('Custom options manager'));
		}
		protected function _beforeToHtml()
		{ 
				
				$this->addTab('product_option_section', array(
				'label' => Mage::helper('emipro_customoptions')->__('Custom Options'),
				'title' => Mage::helper('emipro_customoptions')->__('Custom Options'),
				'content' =>$this->getLayout()->createBlock('emipro_customoptions/adminhtml_manageoptions_edit_tab_grid')->toHtml()
				));
				
				
				return parent::_beforeToHtml();
		}

}
