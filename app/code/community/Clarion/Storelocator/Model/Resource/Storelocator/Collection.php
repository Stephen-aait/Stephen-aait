<?php
/**
 * Storelocation Collection Resource Model
 * 
 * @category    Clarion
 * @package     Clarion_Storelocator
 * @author      Clarion Magento Team
 * 
 */
class Clarion_Storelocator_Model_Resource_Storelocator_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Define collection model
     *
     */
    protected function _construct()
    {
       $this->_init('clarion_storelocator/storelocator');
       $this->_map['fields']['storelocator_id'] = 'main_table.storelocator_id';
       $this->_map['fields']['store']   = 'store_table.store_id';
    }
    
    /**
     * Specify filter by "is_visible" field
     *
     * @return Clarion_Storelocator_Model_Resource_Storelocator_Collection
     */
    public function addStatusFilter()
    {
        return $this->addFieldToFilter('status', 1);
    }
    
    /**
     * Prepare for displaying in list
     *
     * @param integer $page
     * @return Clarion_Storelocator_Model_Resource_Storelocator_Collection
     */
    public function prepareForList($page)
    {
        //Set collection page size
        $this->setPageSize(Mage::helper('clarion_storelocator')->getStoresPerPage());
        //Set current page
        $this->setCurPage($page);
        //Set select order
        $this->setOrder('created_at', Varien_Data_Collection::SORT_ORDER_DESC);
        return $this;
    }
    
    /**
     * Add filter by store
     *
     * @param int|Mage_Core_Model_Store $store
     * @param bool $withAdmin
     * @return Clarion_Storelocator_Model_Resource_Storelocator_Collection
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if (!$this->getFlag('store_filter_added')) {
            if ($store instanceof Mage_Core_Model_Store) {
                $store = array($store->getId());
            }

            if (!is_array($store)) {
                $store = array($store);
            }

            if ($withAdmin) {
                $store[] = Mage_Core_Model_App::ADMIN_STORE_ID;
            }

            $this->addFilter('store', array('in' => $store), 'public');
        }
        return $this;
    }
    
     /**
     * Join store relation table if there is store filter
     */
    protected function _renderFiltersBefore()
    {
        if ($this->getFilter('store')) {
            $this->getSelect()->join(
                array('store_table' => $this->getTable('clarion_storelocator/storelocator_store')),
                'main_table.storelocator_id = store_table.storelocator_id',
                array()
            );
        }
        return parent::_renderFiltersBefore();
    }
}