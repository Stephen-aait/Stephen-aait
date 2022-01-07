<?php
class Emipro_Customoptions_Block_Adminhtml_Categoryoptions_Edit_Tab_Categories extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Categories
{
	protected $_categoryIds;
	protected $_selectedNodes = null;
	
		public function __construct() {
			parent::__construct();
			$this->setTemplate('customoptions/categories.phtml');
		}
			
		protected function getCategoryIds() {
			 return null;
		}	

		public function isReadonly() {
			 return false;
		}

		public function getIdsString() {
			 return implode(',', $this->getCategoryIds());
		}

		public function getCategorytab() {
			 return Mage::registry('categorytab_data');
		}

}
