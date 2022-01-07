<?php
/**
 * SAGIPL
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * 
 * @category   	SAGIPL
 * @package	Sag_Shippingmessage
 * @copyright  	Copyright (c) 2014 SAGIPL. (http://www.sagipl.com/)
 * @license	http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Free Shipping Message
 *
 * @category	SAGIPL
 * @package	Sag_Shippingmessage
 * @author	Navneet <navneet.kshk@gmail.com>
 */
 
class Sag_Shippingmessage_Block_Message extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		parent::_prepareLayout();
    }
	
	public function getShippinmessage(){ 
	
		$_module_enabaled = Mage::getStoreConfig('sagshippingmessage/general/shippingmessage_enabled_select_box',Mage::app()->getStore());
		
		if($_module_enabaled==1){
			if(
				Mage::getStoreConfig('sagshippingmessage/general/shippingmessage_value')!='' &&
				Mage::getStoreConfig('sagshippingmessage/general/shippingmessage_congratulation')!='' &&
				Mage::getStoreConfig('sagshippingmessage/general/shippingmessage_msg')
			){ 
				$configValue = Mage::getStoreConfig('sagshippingmessage/general/shippingmessage_value');
				$subtot = Mage::helper('checkout/cart')->getQuote()->getSubtotal();
				if($subtot!=0.0000){
					if($subtot<$configValue){
						$forfree = $configValue-$subtot;
						return "Shopping more $".$forfree." for free shipping.";
					}else{
						return  Mage::getStoreConfig('sagshippingmessage/general/shippingmessage_congratulation');	
					}
				}else{
					return  Mage::getStoreConfig('sagshippingmessage/general/shippingmessage_msg');
				}
			}else{
				return "Please fill all value for free shipping message extension";	
			}
		}
		
	}

}