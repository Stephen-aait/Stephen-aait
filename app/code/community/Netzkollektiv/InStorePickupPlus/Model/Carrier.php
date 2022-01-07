<?php
class Netzkollektiv_InStorePickupPlus_Model_Carrier
	extends Mage_Shipping_Model_Carrier_Abstract
	implements Mage_Shipping_Model_Carrier_Interface
{

	protected $_code = 'instorepickupplus';

	public function collectRates(Mage_Shipping_Model_Rate_Request $request)
	{
		if (!$this->getConfigFlag('active')) {
			return false;
		}
		
		if (Mage::getDesign()->getArea() != 'adminhtml'
			&& !$this->getConfigData('active_in_frontend')
		) {
			return false;
		}

		$result = Mage::getModel('shipping/rate_result');

		if (!empty($result)) {
			$method = Mage::getModel('shipping/rate_result_method');

			$method->setCarrier('instorepickupplus');
			$method->setCarrierTitle($this->getConfigData('title'));
			$method->setMethod('instorepickupplus');
			$method->setMethodTitle(
				Mage::helper('shipping')->__($this->getConfigData('name'))
			);

			$price = $this->getFinalPriceWithHandlingFee(0);

			$method->setPrice($price);
			$method->setCost($this->getConfigData('handling'));

			$result->append($method);
		}

		return $result;
	}

	/**
 	* Get allowed shipping methods
 	*
 	* @return array
 	*/
	public function getAllowedMethods()
	{
		return array('instorepickupplus'=> Mage::helper('shipping')->__('In-Store Pickup'));
	}
}
