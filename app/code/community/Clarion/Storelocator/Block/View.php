<?php
/**
 * Store View block
 * 
 * @category    Clarion
 * @package     Clarion_Storelocator
 * @author      Clarion Magento Team
 * 
 */
class Clarion_Storelocator_Block_View extends Mage_Core_Block_Template
{ 
    /**
     * Current news item instance
     *
     * @var Clarion_Storelocator_Model_Storelocator
     */
    protected $_store;

    /**
     * Return parameters for back url
     *
     * @param array $additionalParams
     * @return array
     */
    protected function _getBackUrlQueryParams($additionalParams = array())
    {
        return array_merge(array('p' => $this->getPage()), $additionalParams);
    }

    /**
     * Return URL to the store view page
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/', array('_query' => $this->_getBackUrlQueryParams()));
    }

    /**
     * Return URL for resized News Item image
     *
     * @param Clarion_Storelocator_Model_Storelocator $store
     * @param integer $width
     * @return string|false
     */
    public function getImageUrl($store, $width)
    {
        return Mage::helper('clarion_storelocator/image')->resize($store, $width);
    }
    
    /**
     * Return store zoom level/default zoom level
     *
     * @param Clarion_Storelocator_Model_Storelocator $storelocator
     * @return int
     */
    public function getZoomLevel($storelocator=NULL)
    {
        //Return store zoom level
        if(!is_null($storelocator)) {
            if($storelocator->getZoomLevel()){
                return $storelocator->getZoomLevel();
            } else {
                // Return default config zoom level
                return Mage::helper('clarion_storelocator')->getConfigZoomLevel();
            }
        } else {
            // Return default config zoom level
            return Mage::helper('clarion_storelocator')->getConfigZoomLevel();
        }
        return;
    }
    
    /**
     * Return store radius/default radius
     *
     * @param Clarion_Storelocator_Model_Storelocator $storelocator
     * @return int
     */
    public function getRadius($storelocator=NULL)
    {
        //Return store radius
        if(!is_null($storelocator)) {
            if($storelocator->getRadius()){
                return $storelocator->getRadius();
            } else {
                // Return default config radius
                return Mage::helper('clarion_storelocator')->getConfigRadius();
            }
        } else {
            // Return default config radius
            return Mage::helper('clarion_storelocator')->getConfigRadius();
        }
        return;
    }
    
    /**
     * get country name by county code
     * @param string $countryCode country code
     * @return string
     */
    public function getCountryName($countryCode)
    {
        return $this->helper('clarion_storelocator')->getCountryNameByCode($countryCode);
    }
}