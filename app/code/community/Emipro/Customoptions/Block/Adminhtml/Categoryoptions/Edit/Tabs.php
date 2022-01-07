<?php
class Emipro_Customoptions_Block_Adminhtml_Categoryoptions_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId('categoryoptions_tabs');
				$this->setDestElementId('edit_form');
				$this->setTitle(Mage::helper('emipro_customoptions')->__('Custom options manager'));
		}
		protected function _beforeToHtml()
		{ 

				$this->addTab('category_section', array(
				'label' => Mage::helper('emipro_customoptions')->__('Assign to Category'),
				'title' => Mage::helper('emipro_customoptions')->__('Assign to Category'),
				'url'   => $this->getUrl('*/*/categories', array('_current' => true)),
				'class' => 'ajax',
				));
				return parent::_beforeToHtml();
		}

}
