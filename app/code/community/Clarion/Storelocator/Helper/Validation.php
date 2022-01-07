<?php
/**
 * PHP validation helper
 * 
 * @category    Clarion
 * @package     Clarion_Storelocator
 * @author      Clarion Magento Team <magento@clariontechnologies.co.in>
 */
class Clarion_Storelocator_Helper_Validation extends Mage_Core_Helper_Abstract
{
    /**
     * check is valid image
     * @param sting $imageName image
     * @return boolean true/false
     */
    public function isValidImage($imageName)
    {
        $allowededExtensions = array('jpg','jpeg','gif','png');
        $fileExtension = pathinfo($imageName, PATHINFO_EXTENSION);
        if(in_array($fileExtension, $allowededExtensions)){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * check is valid store
     * @param sting $stores stores (comma seperated)
     * @return boolean true/false
     */
    public function isValidStores($stores = '')
    {
        
        if(empty($stores)){
            return false;
        }
        
        $storeCodes = explode(',', $stores);
        foreach($storeCodes as $storeCode){
            $storeId = Mage::getModel('core/store')->load($storeCode, 'code')->getId();
            
            if (!is_numeric($storeId)) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * check is valid url string
     * @param sting $url url
     * @return boolean true/false
     */
    public function isValidUrl($url = '')
    {
        if (!preg_match("/^(http|https|ftp):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+.(com|org|net|dk|at|us|tv|info|uk|co.uk|biz|se)$)(:(\d+))?\/?/i", $url)) {
            if (!preg_match("/^(www)((\.[A-Z0-9][A-Z0-9_-]*)+.(com|org|net|dk|at|us|tv|info|uk|co.uk|biz|se)$)(:(\d+))?\/?/i", $url)) {
                return false;
            } else {
                return true;
            }
        } else {
             return true;
        }
    }
    
    /**
     * check is valid email
     * @param sting $email email
     * @return boolean true/false
     */
    public function isValidEmail($email='')
    {
        if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $email)) {
            return false;
        } else {
            return true;
        }
    }
   
}
