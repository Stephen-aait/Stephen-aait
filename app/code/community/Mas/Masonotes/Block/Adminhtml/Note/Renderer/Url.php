<?php
/**
 * Mas_Mascallback extension by Makarovsoft.com
 * 
 * @category   	Mas
 * @package		Mas_Mascallback
 * @copyright  	Copyright (c) 2014
 * @license		http://makarovsoft.com/license.txt
 * @author		makarovsoft.com
 */
/**
 * Request admin edit block
 *
 * @category	Mas
 * @package		Mas_Mascallback
 * 
 */
class Mas_Masonotes_Block_Adminhtml_Note_Renderer_Url  extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {
	public function render(Varien_Object $row)
	{
        $wrap = sprintf('<a href="%s" target="_blank">%s</a>', $this->getUrl('adminhtml/sales_order/view', array('order_id' => $row->getOrderId())), $row->getIncrementId());			
		return $wrap;
	}
}