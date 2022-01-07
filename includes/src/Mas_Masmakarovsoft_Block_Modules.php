<?php 
class Mas_Masmakarovsoft_Block_Modules extends Mage_Adminhtml_Block_System_Config_Form_Fieldset {
	
	public function render(Varien_Data_Form_Element_Abstract $el)
	{
		return '<iframe style="width: 100%;height: 2000px;border: none;" src="//ecommerce.makarovsoft.com/store-view?store_mode=1" frameborder="0"></iframe>';
	}
} 