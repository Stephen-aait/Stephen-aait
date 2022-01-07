<?php
class Netzkollektiv_InStorePickupPlus_Model_Payment extends Mage_Payment_Model_Method_Abstract
{
	protected $_code = 'instorepickupplus_cash';

	protected $_formBlockType = 'instorepickupplus/cash_form';
	protected $_infoBlockType = 'instorepickupplus/cash_info';

	public function isAvailable($quote=null)
	{
        if (is_null($quote)
            || !$this->getConfigData('active')
        ) { 
            return false;
        }

		$shippingMethod = Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getShippingMethod();
		
		if (!$this->getConfigData('active_in_frontend') 
			&& Mage::getDesign()->getArea() != 'adminhtml') {
			return false;
		}

		if ('instorepickupplus_instorepickupplus' == $shippingMethod 
			|| Mage::getDesign()->getArea() == 'adminhtml'
			|| $this->getConfigData('ignore_shipping_method')) {
			return true;
		}
		return false;
	}
}
