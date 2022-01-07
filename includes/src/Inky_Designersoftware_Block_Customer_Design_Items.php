<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Wishlist
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Wishlist block customer items
 *
 * @category    Mage
 * @package     Mage_Wishlist
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Inky_Designersoftware_Block_Customer_Design_Items extends Inky_Designersoftware_Block_Abstract
{	
	
	public function isAddToCartEnable(){
		
		return $this->helper('designersoftware/config')->isAddToCartEnable();
		
	}
	
	public function isRemoveEnable(){
		
		return $this->helper('designersoftware/config')->isRemoveEnable();
		
	}
		
	// Provides Designs By the Current Customer    
    public function getItems(){
		$items = Mage::getModel('designersoftware/designersoftware')->getCustomerDesignCollection();
		//Mage::log($items->getData(),null,'designersoftware.log');
		return $items;
	}
	
	public function getDesignerProductEditUrl($params){
		//Mage::log("PARAMS:".$params,null,'designersoftware.log');
		return $this->_model->getDesignerProductEditUrl($params);
		
	}
	
	public function getDesignerProductParams($collection){
		
		return $this->_model->getDesignerProductParams($collection);
		
	}
	
	public function getDesignerProductImage($imageName){
		
		return $this->_model->getDesignerProductImage($imageName);
		
	}
	
	public function getAddtoCartUrl($collection){
		$designId			= $collection->getId();
		$designCode			= $collection->getStyleDesignCode();
		$productId 			= $collection->getProductId();
		$customOptionsUrl 	= Mage::helper('designersoftware/customoption')->sendCustomOptionUrl($productId, $designCode); 
		
		$product			= Mage::getModel('catalog/product')->load($productId);
		
		$cartUrl=Mage::helper('checkout/cart')->getAddUrl($product).'?qty=1&dId='.$designId . $customOptionsUrl;																	 
		
		return $cartUrl;
	}
	
	// To Remove Design from Design Section of Customer
	public function getRemoveUrl($collection)
    {
        return $this->helper('designersoftware')->getRemoveUrl($collection);
    }	
}
