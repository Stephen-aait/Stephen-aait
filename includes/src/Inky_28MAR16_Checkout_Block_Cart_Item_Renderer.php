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
 * @package     Mage_Checkout
 * @copyright  Copyright (c) 2006-2014 X.commerce, Inc. (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Shopping cart item render block
 *
 * @category    Mage
 * @package     Mage_Checkout
 * @author      Magento Core Team <core@magentocommerce.com>
 *
 * @method Mage_Checkout_Block_Cart_Item_Renderer setProductName(string)
 * @method Mage_Checkout_Block_Cart_Item_Renderer setDeleteUrl(string)
 */
class Inky_Checkout_Block_Cart_Item_Renderer extends Mage_Core_Block_Template
{
    /** @var Mage_Checkout_Model_Session */
    protected $_checkoutSession;
    protected $_item;
    protected $_productUrl = null;
    protected $_productThumbnail = null;

    /**
     * Whether qty will be converted to number
     *
     * @var bool
     */
    protected $_strictQtyMode = true;

    /**
     * Check, whether product URL rendering should be ignored
     *
     * @var bool
     */
    protected $_ignoreProductUrl = false;
    
	
	 public function getDesignerProductCode(){
		if($_options = $this->getOptionList()):        
			foreach($_options as $_option):
				if(strtolower($_option['label'])=='design code'):
					$designCode = trim($_option['value']);
					return $designCode;
					break;
				endif;
			endforeach;        
		endif;
		
		return false;
	}
    
    public function getDesignerProduct(){
		$designCode = $this->getDesignerProductCode();
		// DesignerSoftware 
		$designersoftwareModel = Mage::getModel('designersoftware/designersoftware')->getCollection()
									->setOrder('designersoftware_id','DESC')
									->addFieldToFilter('style_design_code', $designCode)
									->addFieldToFilter('status',1)
									->getFirstItem();
		
		return $designersoftwareModel;		
	}
	
	/**
     * Set item for render
     *
     * @param   Mage_Sales_Model_Quote_Item $item
     * @return  Mage_Checkout_Block_Cart_Item_Renderer
     */
    public function setItem(Mage_Sales_Model_Quote_Item_Abstract $item)
    {		
        $this->_item = $item;
        return $this;
    }
	
    /**
     * Get quote item
     *
     * @return Mage_Sales_Model_Quote_Item
     */
    public function getItem()
    {				
        return $this->_item;
    }

    /**
     * Get item product
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {		
        return $this->getItem()->getProduct();
    }

    public function overrideProductThumbnail($productThumbnail)
    {
        $this->_productThumbnail = $productThumbnail;
        return $this;
    }

    /**
     * Get product thumbnail image
     *
     * @return Mage_Catalog_Model_Product_Image
     */
    public function getProductThumbnail()
    {
		$designCode = $this->getDesignerProductCode();
		$image = '/designersoftware/'.$designCode.'/000.png';
		
        if (!is_null($this->_productThumbnail)) {
            return $this->_productThumbnail;
        }	
        	
		if($designCode):			
			return $this->helper('catalog/image')->init($this->getProduct(), 'thumbnail',$image);
		else:		
			return $this->helper('catalog/image')->init($this->getProduct(), 'thumbnail');
		endif;
    }

    public function overrideProductUrl($productUrl)
    {
        $this->_productUrl = $productUrl;
        return $this;
    }

    /**
     * Check Product has URL
     *
     * @return bool
     */
    public function hasProductUrl()
    {
        if ($this->_ignoreProductUrl) {
            return false;
        }
        if ($this->_productUrl || $this->getItem()->getRedirectUrl()) {
            return true;
        }

        $product = $this->getProduct();
        $option  = $this->getItem()->getOptionByCode('product_type');
        if ($option) {
            $product = $option->getProduct();
        }
        if ($product->isVisibleInSiteVisibility()) {
            return true;
        }
        return false;
    }
    
    public function getDesignerToolUrl(){
		// 'Design Your Own' category Id 		
		$designerToolCategoryId = Mage::getModel('core/variable')->loadByCode('DESIGNER_TOOL_CATEGORY_ID')->getValue('plain');
		$_categoryCollection = Mage::getModel('catalog/category')->load($designerToolCategoryId);
		
		$params = $this->getDesignerProductParams();		
		return $_categoryCollection->getUrl().'?'.$params;
	}   
    
    public function getDesignerProductParams(){	
		$designersoftwareModel = $this->getDesignerProduct();	
		if($designersoftwareModel['designersoftware_id']>0):
								
			$params='';			
			$params .='did='.$designersoftwareModel['designersoftware_id'];
			$params .='&';
			$params .='mode=edit'; 
			//$params .='&';
			//$params .='code='.$designCode;
			
			$params = base64_encode($params);			
			return $params;
		endif;
	}

    /**
     * Retrieve URL to item Product
     *
     * @return string
     */
    public function getProductUrl()
    {		
        if (!is_null($this->_productUrl)) {
            return $this->_productUrl;
        }
        if ($this->getItem()->getRedirectUrl()) {
            return $this->getItem()->getRedirectUrl();
        }

        $product = $this->getProduct();
        $option  = $this->getItem()->getOptionByCode('product_type');
        if ($option) {
            $product = $option->getProduct();
        }		
		
		// ================ Added code for DESIGNER PRODUCT =========================
		$designerProductId = Mage::getModel('core/variable')->loadByCode('DESIGNER_TOOL_PRODUCT_ID')->getValue('plain');
		$productId = $product->getId();
		if($productId==$designerProductId):
			$params = $this->getDesignerProductParams();				
			return $product->getUrlModel()->getUrl($product).'?'.$params;
		endif;
		// ================ Added code for DESIGNER PRODUCT =========================
		
        return $product->getUrlModel()->getUrl($product);        
    }

    /**
     * Get item product name
     *
     * @return string
     */
    public function getProductName()
    {
        if ($this->hasProductName()) {
            return $this->getData('product_name');
        }
        return $this->getProduct()->getName();
    }

    /**
     * Get product customize options
     *
     * @return array || false
     */
    public function getProductOptions()
    {
        /* @var $helper Mage_Catalog_Helper_Product_Configuration */
        $helper = Mage::helper('catalog/product_configuration');
        return $helper->getCustomOptions($this->getItem());
    }

    /**
     * Get list of all otions for product
     *
     * @return array
     */
    public function getOptionList()
    {
        return $this->getProductOptions();
    }

    /**
     * Get item configure url
     *
     * @return string
     */
    public function getConfigureUrl()
    {
		$designerProductId = Mage::getModel('core/variable')->loadByCode('DESIGNER_TOOL_PRODUCT_ID')->getValue('plain');
		$product = $this->getProduct();		
		$productId = $product->getId();
		
		if($productId==$designerProductId):
			return $this->getDesignerToolUrl();
		else:
			return $this->getUrl(
				'checkout/cart/configure',
				array('id' => $this->getItem()->getId())
			);
		endif;
    }

    /**
     * Get item delete url
     *
     * @return string
     */
    public function getDeleteUrl()
    {
        if ($this->hasDeleteUrl()) {
            return $this->getData('delete_url');
        }

        return $this->getUrl(
            'checkout/cart/delete',
            array(
                'id'=>$this->getItem()->getId(),
                Mage_Core_Controller_Front_Action::PARAM_NAME_URL_ENCODED => $this->helper('core/url')->getEncodedUrl()
            )
        );
    }

    /**
     * Get item ajax delete url
     *
     * @return string
     */
    public function getAjaxDeleteUrl()
    {
        return $this->getUrl(
            'checkout/cart/ajaxDelete',
            array(
                'id'=>$this->getItem()->getId(),
                Mage_Core_Controller_Front_Action::PARAM_NAME_URL_ENCODED => $this->helper('core/url')->getEncodedUrl(),
                '_secure' => $this->_getApp()->getStore()->isCurrentlySecure(),
            )
        );
    }

    /**
     * Get item ajax update url
     *
     * @return string
     */
    public function getAjaxUpdateUrl()
    {
        return $this->getUrl(
            'checkout/cart/ajaxUpdate',
            array(
                'id'=>$this->getItem()->getId(),
                Mage_Core_Controller_Front_Action::PARAM_NAME_URL_ENCODED => $this->helper('core/url')->getEncodedUrl(),
                '_secure' => $this->_getApp()->getStore()->isCurrentlySecure(),
            )
        );
    }
    /**
     * Get quote item qty
     *
     * @return float|int|string
     */
    public function getQty()
    {
        if (!$this->_strictQtyMode && (string)$this->getItem()->getQty() == '') {
            return '';
        }
        return $this->getItem()->getQty() * 1;
    }

    /**
     * Check item is in stock
     *
     * @deprecated after 1.4.2.0-beta1
     * @return bool
     */
    public function getIsInStock()
    {
        if ($this->getItem()->getProduct()->isSaleable()) {
            if ($this->getItem()->getProduct()->getStockItem()->getQty() >= $this->getItem()->getQty()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get checkout session
     *
     * @return Mage_Checkout_Model_Session
     */
    public function getCheckoutSession()
    {
        if (null === $this->_checkoutSession) {
            $this->_checkoutSession = Mage::getSingleton('checkout/session');
        }
        return $this->_checkoutSession;
    }

    /**
     * Retrieve item messages
     * Return array with keys
     *
     * text => the message text
     * type => type of a message
     *
     * @return array
     */
    public function getMessages()
    {
        $messages = array();
        $quoteItem = $this->getItem();

        // Add basic messages occuring during this page load
        $baseMessages = $quoteItem->getMessage(false);
        if ($baseMessages) {
            foreach ($baseMessages as $message) {
                $messages[] = array(
                    'text' => $message,
                    'type' => $quoteItem->getHasError() ? 'error' : 'notice'
                );
            }
        }

        // Add messages saved previously in checkout session
        $checkoutSession = $this->getCheckoutSession();
        if ($checkoutSession) {
            /* @var $collection Mage_Core_Model_Message_Collection */
            $collection = $checkoutSession->getQuoteItemMessages($quoteItem->getId(), true);
            if ($collection) {
                $additionalMessages = $collection->getItems();
                foreach ($additionalMessages as $message) {
                    /* @var $message Mage_Core_Model_Message_Abstract */
                    $messages[] = array(
                        'text' => $message->getCode(),
                        'type' => ($message->getType() == Mage_Core_Model_Message::ERROR) ? 'error' : 'notice'
                    );
                }
            }
        }

        return $messages;
    }

    /**
     * Accept option value and return its formatted view
     *
     * @param mixed $optionValue
     * Method works well with these $optionValue format:
     *      1. String
     *      2. Indexed array e.g. array(val1, val2, ...)
     *      3. Associative array, containing additional option info, including option value, e.g.
     *          array
     *          (
     *              [label] => ...,
     *              [value] => ...,
     *              [print_value] => ...,
     *              [option_id] => ...,
     *              [option_type] => ...,
     *              [custom_view] =>...,
     *          )
     *
     * @return array
     */
    public function getFormatedOptionValue($optionValue)
    {
        /* @var $helper Mage_Catalog_Helper_Product_Configuration */
        $helper = Mage::helper('catalog/product_configuration');
        $params = array(
            'max_length' => 55,
            'cut_replacer' => ' <a href="#" class="dots" onclick="return false">...</a>'
        );
        return $helper->getFormattedOptionValue($optionValue, $params);
    }

    /**
     * Check whether Product is visible in site
     *
     * @return bool
     */
    public function isProductVisible()
    {
        return $this->getProduct()->isVisibleInSiteVisibility();
    }

    /**
     * Return product additional information block
     *
     * @return Mage_Core_Block_Abstract
     */
    public function getProductAdditionalInformationBlock()
    {
        return $this->getLayout()->getBlock('additional.product.info');
    }

    /**
     * Get html for MAP product enabled
     *
     * @param Mage_Sales_Model_Quote_Item $item
     * @return string
     */
    public function getMsrpHtml($item)
    {
        return $this->getLayout()->createBlock('catalog/product_price')
            ->setTemplate('catalog/product/price_msrp_item.phtml')
            ->setProduct($item->getProduct())
            ->toHtml();
    }

    /**
     * Set qty mode to be strict or not
     *
     * @param bool $strict
     * @return Mage_Checkout_Block_Cart_Item_Renderer
     */
    public function setQtyMode($strict)
    {
        $this->_strictQtyMode = $strict;
        return $this;
    }

    /**
     * Set ignore product URL rendering
     *
     * @param bool $ignore
     * @return Mage_Checkout_Block_Cart_Item_Renderer
     */
    public function setIgnoreProductUrl($ignore = true)
    {
        $this->_ignoreProductUrl = $ignore;
        return $this;
    }

    /**
     * Common code to be called by product renders of gift registry to create a block, which is be used to
     * generate html for mrsp price
     *
     * @param Mage_Catalog_Model_Product $product
     * @return Mage_Catalog_Block_Product_Price
     */
    protected function _preparePriceBlock($product)
    {
        return $this->getLayout()
            ->createBlock('catalog/product_price')
            ->setTemplate('catalog/product/price.phtml')
            ->setIdSuffix($this->getIdSuffix())
            ->setProduct($product);
    }

    /**
     *  Common code to be called by product renders of gift registry to  generate final html block
     *
     * @param Mage_Catalog_Model_Product $product
     * @return string
     */
    protected function _getPriceContent($product)
    {
        return $this->getLayout()->createBlock('catalog/product_price')
            ->setTemplate('catalog/product/price_msrp.phtml')
            ->setProduct($product)
            ->toHtml();
    }

    /**
     * Retrieve block cache tags
     *
     * @return array
     */
    public function getCacheTags()
    {
        $tags = $this->getProduct()->getCacheIdTags();
        $tags = is_array($tags) ? $tags : array();

        return array_merge(parent::getCacheTags(), $tags);
    }

    /**
     * Returns true if user is going through checkout process now.
     *
     * @return bool
     */
    public function isOnCheckoutPage()
    {
        $module = $this->getRequest()->getModuleName();
        $controller = $this->getRequest()->getControllerName();
        return $module == 'checkout' && ($controller == 'onepage' || $controller == 'multishipping');
    }
}
