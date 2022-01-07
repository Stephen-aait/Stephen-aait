<?php
/**
 * Storelocation Resource Model
 * 
 * @category    Clarion
 * @package     Clarion_Storelocator
 * @author      Clarion Magento Team <magento@clariontechnologies.co.in>
 * 
 */
class Clarion_Storelocator_Model_Resource_Storelocator extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Errors in import process
     *
     * @var array
     */
    protected $_importErrors        = array();

    /**
     * Count of imported stores
     *
     * @var int
     */
    protected $_importedRows        = 0;
    
    /**
     * Overwrite existing store(s)
     * 1=yes, 0=no
     * @var int
     */
    protected $_behavior        = 0;
    
    protected function _construct()
    {
        $this->_init('clarion_storelocator/storelocator', 'storelocator_id');
    }
    
    /**
     * Process storelocator data before deleting
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Clarion_Storelocator_Model_Resource_Storelocator
     */
    protected function _beforeDelete(Mage_Core_Model_Abstract $object)
    {
        $condition = array(
            'storelocator_id = ?'     => (int) $object->getId(),
        );

        $this->_getWriteAdapter()->delete($this->getTable('clarion_storelocator/storelocator_store'), $condition);

        return parent::_beforeDelete($object);
    }
    
    /**
     * Check if store exists
     *
     * @param $storeName store name
     * @param $storelocatorId storelocaror id
     * @return array|false
     */
    public function storeExists($storeName, $storelocatorId)
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select();

        if(empty($storelocatorId)){
            $binds = array(
              'name' => $storeName,
            );
            
             $select->from($this->getMainTable())
             ->where('(name = :name)');
        } else {
            $binds = array(
              'name' => $storeName,
              'storelocator_id'  => (int) $storelocatorId,
            ); 
            
            $select->from($this->getMainTable())
            ->where('(name = :name)')
            ->where('storelocator_id <> :storelocator_id');
        }
        return $adapter->fetchRow($select, $binds);
    }
    
    /**
     * Assign storelocator to store views
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Clarion_Storelocator_Model_Resource_Storelocator
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $oldStores = $this->lookupStoreIds($object->getId());
        $newStores = (array)$object->getStores();
        if (empty($newStores)) {
            $newStores = (array)$object->getStoreId();
        }
        $table  = $this->getTable('clarion_storelocator/storelocator_store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);

        if ($delete) {
            $where = array(
                'storelocator_id = ?'     => (int) $object->getId(),
                'store_id IN (?)' => $delete
            );

            $this->_getWriteAdapter()->delete($table, $where);
        }

        if ($insert) {
            $data = array();

            foreach ($insert as $storeId) {
                $data[] = array(
                    'storelocator_id'  => (int) $object->getId(),
                    'store_id' => (int) $storeId
                );
            }

            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }

        return parent::_afterSave($object);
    }
    
    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $storelocatorId
     * @return array
     */
    public function lookupStoreIds($storelocatorId)
    {
        $adapter = $this->_getReadAdapter();

        $select  = $adapter->select()
            ->from($this->getTable('clarion_storelocator/storelocator_store'), 'store_id')
            ->where('storelocator_id = ?',(int)$storelocatorId);
        return $adapter->fetchCol($select);
    }
    
    /**
     * Perform operations after object load
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Mage_Cms_Model_Resource_Page
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());

            $object->setData('store_id', $stores);

        }
        return parent::_afterLoad($object);
    }
    
    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param Mage_Cms_Model_Page $object
     * @return Zend_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);

        if ($object->getStoreId()) {
            $storeIds = array(Mage_Core_Model_App::ADMIN_STORE_ID, (int)$object->getStoreId());
            $select->join(
                array('clarion_storelocator_store' => $this->getTable('clarion_storelocator/storelocator_store')),
                $this->getMainTable() . '.storelocator_id = clarion_storelocator_store.storelocator_id',
                array())
                ->where('status = ?', 1)
                ->where('clarion_storelocator_store.store_id IN (?)', $storeIds)
                ->order('clarion_storelocator_store.store_id DESC')
                ->limit(1);
        }
        return $select;
    }
    
    /**
     * Upload store csv file and import data from it
     *
     * @throws Mage_Core_Exception
     * @param Varien_Object $object
     * @return Clarion_Storelocator_Model_Storelocator
     */
    public function uploadAndImport($object)
    {
        $this->_behavior = $object->getRequest()->getPost('behavior');
        $importFile = $_FILES['import_file'];
        if (empty($importFile['tmp_name'])) {
            return ;
        }
        
        $csvFile = $importFile['tmp_name'];

        $this->_importErrors        = array();
        $this->_importedRows        = 0;

        $io     = new Varien_Io_File();
        $info   = pathinfo($csvFile);
        $io->open(array('path' => $info['dirname']));
        $io->streamOpen($info['basename'], 'r');
        
        // check and skip headers
        $headers = $io->streamReadCsv();
        if ($headers === false || count($headers) < 19) {
            $io->streamClose();
            Mage::throwException(Mage::helper('clarion_storelocator')->__('Invalid Stores File Format'));
        }
        
        $adapter = $this->_getWriteAdapter();
        $adapter->beginTransaction();
        try {
            $rowNumber  = 1;
            $importData = array();
            
            while (false !== ($csvLine = $io->streamReadCsv())) {
                $rowNumber ++;

                if (empty($csvLine)) {
                    continue;
                }

                $row = $this->_getImportRow($csvLine, $rowNumber);
                
                if ($row !== false) {
                    $importData[] = $row;
                }

                if (count($importData) == 5000) {
                    $this->_saveImportData($importData);
                    $importData = array();
                }
            }
            
            if ($this->_importErrors) {
                $error = Mage::helper('clarion_storelocator')->__('File has not been imported. See the following list of errors: <br>%s', implode(" <br>", $this->_importErrors));
                Mage::throwException($error);
            }
            
            if(empty($this->_importErrors)){
                $this->_saveImportData($importData);
            }
           
            $io->streamClose();
        } catch (Mage_Core_Exception $e) {
            $adapter->rollback();
            $io->streamClose();
            Mage::throwException($e->getMessage());
        } catch (Exception $e) {
            $adapter->rollback();
            $io->streamClose();
            Mage::logException($e);
            Mage::throwException(Mage::helper('clarion_storelocator')->__('An error occurred while import stores csv.'));
        }
        
        $adapter->commit();
        
        //add success message
        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('clarion_storelocator')->__($this->_importedRows.' - Rows imported successfully.'));
        return $this;
    }
    
    /**
     * Validate row for import and return stores array or false
     * Error will be add to _importErrors array
     *
     * @param array $row
     * @param int $rowNumber
     * @return array|false
     */
    protected function _getImportRow($row, $rowNumber = 0)
    {
        // validate row
        if (count($row) < 18) {
            $this->_importErrors[] = Mage::helper('clarion_storelocator')->__('Invalid stores format in the Row #%s', $rowNumber);
            return false;
        }

        // strip whitespace from the beginning and end of each row
        foreach ($row as $k => $v) {
            $row[$k] = trim($v);
        }

        // validate store name
        if(empty($row[0])){
            $this->_importErrors[] = Mage::helper('clarion_storelocator')->__('Invalid Store Name "%s" in the Row #%s.', $row[0], $rowNumber);
            return false;
        }
        // validate country
        $countryId = '';
        if(!empty($row[1])){
            $countryId = $this->_getCountryCountryCode($row[1]);
        }
        if(empty($countryId) || ($countryId === false)){
           $this->_importErrors[] = Mage::helper('shipping')->__('Invalid Country "%s" in the Row #%s.', $row[1], $rowNumber);
            return false;
        }
        
        // validate state
        if(empty($row[2])){
            $this->_importErrors[] = Mage::helper('clarion_storelocator')->__('Invalid State "%s" in the Row #%s.', $row[2], $rowNumber);
            return false;
        }
        
        // validate city
        if(empty($row[3])){
            $this->_importErrors[] = Mage::helper('clarion_storelocator')->__('Invalid City "%s" in the Row #%s.', $row[3], $rowNumber);
            return false;
        }
        
        // validate Zipcode
        if(empty($row[4])){
            $this->_importErrors[] = Mage::helper('clarion_storelocator')->__('Invalid Zipcode "%s" in the Row #%s.', $row[4], $rowNumber);
            return false;
        }
        
        // validate stores
        $isValidStoreString = Mage::helper('clarion_storelocator/validation')->isValidStores($row[5]);
        
        if($isValidStoreString === false){
          $this->_importErrors[] = Mage::helper('clarion_storelocator')->__('Invalid Stores "%s" in the Row #%s.', $row[5], $rowNumber);
          return false;
        }
        //get store ids from store code
        $storeIds = array();
        
        if(!empty($row[5])){
            $storeCodes = explode(',', $row[5]);
            foreach($storeCodes as $storeCode){
                $storeId = Mage::getModel('core/store')->load($storeCode, 'code')->getId();
                $storeIds[] = $storeId;
            }
        }
        
        // validate Status
           if($row[6] != 'Enable' && $row[6] != 'Disable'){
           $this->_importErrors[] = Mage::helper('clarion_storelocator')->__('Invalid Status "%s" in the Row #%s.', $row[6], $rowNumber);
            return false;
        }
        $status = ($row[6] == 'Enable') ? 1 :(($row[6] == 'Disable') ? 0 : '');
        
        //validate url
        if(!empty($row[10])){
           $isValidSiteUrl = Mage::helper('clarion_storelocator/validation')->isValidUrl($row[10]); 
            if($isValidSiteUrl === false){
                $this->_importErrors[] = Mage::helper('clarion_storelocator')->__('Invalid URL "%s" in the Row #%s.', $row[10], $rowNumber);
                return false;
           }
        }
        
        //validate email
        if(!empty($row[11])){
           $isValidEmailAddress = Mage::helper('clarion_storelocator/validation')->isValidEmail($row[11]); 
            if($isValidEmailAddress === false){
                $this->_importErrors[] = Mage::helper('clarion_storelocator')->__('Invalid Email "%s" in the Row #%s.', $row[11], $rowNumber);
                return false;
           }
        }
        
        //validate image
        if(!empty($row[12])){
           $isValidImageName = Mage::helper('clarion_storelocator/validation')->isValidImage($row[12]); 
            if($isValidImageName === false){
                $this->_importErrors[] = Mage::helper('clarion_storelocator')->__('Invalid Image "%s" in the Row #%s.', $row[12], $rowNumber);
                return false;
           }
        }
        //validate radius
        if(!empty($row[14])){
           if(!is_numeric($row[14])){
                $this->_importErrors[] = Mage::helper('clarion_storelocator')->__('Invalid Radius "%s" in the Row #%s.', $row[14], $rowNumber);
                return false;
            }
        }
        
        //validate Latitude
        if(!is_numeric($row[15])){
             $this->_importErrors[] = Mage::helper('clarion_storelocator')->__('Invalid Latitude "%s" in the Row #%s.', $row[15], $rowNumber);
             return false;
         }
         
       //validate Longitude
        if(!is_numeric($row[16])){
             $this->_importErrors[] = Mage::helper('clarion_storelocator')->__('Invalid Longitude "%s" in the Row #%s.', $row[16], $rowNumber);
             return false;
         }
         
        //Zoom Level
        if(!empty($row[17])){
            if(!is_numeric($row[17])){
                $this->_importErrors[] = Mage::helper('clarion_storelocator')->__('Invalid Zoom Level "%s" in the Row #%s.', $row[17], $rowNumber);
                return false;
            }
        }
        
        $storeRow = array(
            'name'           => $row[0],
            'status'         => $status,
            'street_address' => $row[7],
            'country'        => $countryId,
            'state'          => $row[2],
            'city'           => $row[3],
            'zipcode'        => $row[4],
            'phone'          => $row[8],
            'fax'            => $row[9],
            'url'            => $row[10],
            'email'          => $row[11],
            'store_logo'     => $row[12],
            'description'    => $row[18],
            'trading_hours'  => $row[13],
            'radius'         => $row[14],
            'latitude'       => $row[15],
            'longitude'      => $row[16],
            'zoom_level'     => $row[17],
            'stores'        =>  $storeIds,
            'created_at'     => Mage::getModel('core/date')->timestamp(time()),
            
        );
        return $storeRow;       
    }
    
    /**
     * Get country code from country name
     * @param sting $countryName counrty name
     * @return string $countryId/false
     */
    protected function _getCountryCountryCode($countryName)
    {
        $countryId = '';
        $countryCollection = Mage::getModel('directory/country')->getCollection();
        foreach ($countryCollection as $country) {
            if ($countryName == $country->getName()) {
                $countryId = $country->getCountryId();
                break;
            }
        }
        
        if(empty($countryId)){
            return false;
        }else{
            return $countryId;
        }
    }
    
    /**
     * Save import data batch
     *
     * @param array $stores
     * @return Clarion_Storelocator_Model_Resource_Storelocator
     */
    protected function _saveImportData(array $stores)
    {
        if (!empty($stores)) {
            $transactionSave = Mage::getModel('core/resource_transaction');
            
            foreach($stores as $store){
                $storelocatorId ='';
                $storelocatorModel = Mage::getModel('clarion_storelocator/storelocator');
               
                //check is store already exits
                if(!empty($store['name'])) {
                    $storelocatorModel->load($store['name'],'name');
                    if($storelocatorModel->getId()){
                        $storelocatorId = $storelocatorModel->getId();
                    }
                }
                
                $storelocatorModel->setData($store);
                
                if(!empty($storelocatorId)) {
                    $storelocatorModel->setStorelocatorId($storelocatorId);
                }
                
                //check for overwrite existing store(s) 
                if($this->_behavior == 1 || empty($storelocatorId)){
                    $transactionSave->addObject($storelocatorModel);
                }
            }
            $transactionSave->save();
            $this->_importedRows += count($stores);
        }
        return $this;
    }
}