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
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Catalog
 * @copyright  Copyright (c) 2006-2014 X.commerce, Inc. (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Product view abstract block
 *
 * @category   Mage
 * @package    Mage_Catalog
 * @author      Magento Core Team <core@magentocommerce.com>
 */
abstract class Mage_Catalog_Block_Product_View_Abstract extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * Retrive product
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
        $product = parent::getProduct();
        if (is_null($product->getTypeInstance(true)->getStoreFilter($product))) {
            $product->getTypeInstance(true)->setStoreFilter(Mage::app()->getStore(), $product);
        }
        
        // ================  IF Product is Designer Product =============START=============
        
       /*$params = Mage::helper('designersoftware/product')->getParams($_REQUEST);
        $designId 	= $params['did'];        
        //echo '<pre>';print_r($params);exit;	
        //echo '<pre>';print_r($product->getData());exit;
        if(isset($designId) && $designId>0):
			
			// DesignerSoftware 
			$designersoftwareModel = Mage::getModel('designersoftware/designersoftware')->getCollection()										
										->addFieldToFilter('designersoftware_id', $designId)
										->addFieldToFilter('status',1)
										->getFirstItem();
			
			$image = '/designersoftware/'.$designersoftwareModel->getStyleDesignCode().'/000.png';
			$product->setImage($image);
			$product->setSmallImage($image);
			$product->setThumbnail($image);
			
			$product->setPrice($designersoftwareModel->getTotalPrice());
			$product->save();
			//echo '<pre>';print_r($product->getData());exit;
        endif;*/
        
        // ================  IF Product is Designer Product =============END=============        	
        
        return $product;
    }
}
