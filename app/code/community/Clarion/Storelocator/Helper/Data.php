<?php
/**
 * Store locator data helper
 * 
 * @category    Clarion
 * @package     Clarion_Storelocator
 * @author      Clarion Magento Team
 */
class Clarion_Storelocator_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Path to store config if front-end output is enabled
     *
     * @var string
     */
    const XML_PATH_ENABLED = 'clarion_storelocator_general_setting/clarion_storelocator_status/enable';
    
    /**
     * Default radius
     *
     * @var string
     */
    const XML_PATH_DEFAULT_RADIUS = 'clarion_storelocator_general_setting/clarion_storelocator_display_setting/default_radius';
    
    
    /**
     * Default zoom level
     *
     * @var string
     */
    const XML_PATH_DEFAULT_ZOOM_LEVEl = 'clarion_storelocator_general_setting/clarion_storelocator_display_setting/zoom_level';
    
    /**
     * Default stores per page
     *
     * @var string
     */
    const XML_PATH_STORES_PER_PAGE = 'clarion_storelocator_general_setting/clarion_storelocator_display_setting/stores_per_page';
    
    /**
     * Store view instance for lazy loading
     *
     * @var clarion_storelocator_model_storelocator
     */
    protected $_storeViewInstance;
    
    /**
     * Checks whether news can be displayed in the frontend
     *
     * @param integer|string|Mage_Core_Model_Store $store
     * @return boolean
     */
    public function isEnabled($store = null)
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_ENABLED, $store);
    }
    
    /**
     * Return current store instance from the Registry
     *
     * @return clarion_storelocator_model_storelocator
     */
    public function getStoreViewInstance()
    {
        if (!$this->_storeViewInstance) {
            $this->_storeViewInstance = Mage::registry('store_view');

            if (!$this->_storeViewInstance) {
                Mage::throwException($this->__('Store view instance does not exist in Registry'));
            }
        }

        return $this->_storeViewInstance;
    }
    
    /**
     * Return radius configured from admin
     *
     * @param integer|string|Mage_Core_Model_Store $store
     * @return int
     */
    public function getConfigRadius($store = null)
    {
        return abs(Mage::getStoreConfig(self::XML_PATH_DEFAULT_RADIUS, $store));
    }
    
    /**
     * Return zoom level configured from admin
     *
     * @param integer|string|Mage_Core_Model_Store $store
     * @return int
     */
    public function getConfigZoomLevel($store = null)
    {
        return abs((int)Mage::getStoreConfig(self::XML_PATH_DEFAULT_ZOOM_LEVEl, $store));
    }
    
    /**
     * Return the number of stores per page
     *
     * @param integer|string|Mage_Core_Model_Store $store
     * @return int
     */
    public function getStoresPerPage($store = null)
    {
        return abs((int)Mage::getStoreConfig(self::XML_PATH_STORES_PER_PAGE, $store));
    }
    
    /**
     * Retrieve store search form post url
     *
     * @return string
     */
    public function getSearchPostUrl()
    {
        return $this->_getUrl('storelocator/index/search');
    }
    
    /**
     * Get country name by county code
     * @param string $countryCode country code
     * @return string
     */
    public function getCountryNameByCode($countryCode)
    {
        $countryModel = Mage::getModel('directory/country')->loadByCode($countryCode);
        return $countryName = $countryModel->getName();
    }
    
    /**
     * Maximum size of uploaded files.
     *
     * @return int
     */
    public function getMaxUploadSize()
    {
        return min(ini_get('post_max_size'), ini_get('upload_max_filesize'));
    }
}
