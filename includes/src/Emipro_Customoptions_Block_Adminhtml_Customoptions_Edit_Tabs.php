<?php
class Emipro_Customoptions_Block_Adminhtml_Customoptions_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
				$sku=$this->getRequest()->getParam("sku");
				if(isset($sku))
				{
					$this->addTab('product_grid_section', array(
					'label' => Mage::helper('emipro_customoptions')->__('Assign to Product'),
					'title' => Mage::helper('emipro_customoptions')->__('Assign to Product'),
					'url'       => $this->getUrl('*/*/products', array('_current' => true)),
					'class'     => 'ajax',
					));	
				}
				else
				{
					$this->addTab('product_option_section', array(
				'label' => Mage::helper('emipro_customoptions')->__('Custom Options'),
				'title' => Mage::helper('emipro_customoptions')->__('Custom Options'),
				'content' => $this->getLayout()->createBlock('emipro_customoptions/adminhtml_customoptions_edit_tab_options','admin.product.options')->toHtml(),
				));
				}
				
				return parent::_beforeToHtml();
		}

}
