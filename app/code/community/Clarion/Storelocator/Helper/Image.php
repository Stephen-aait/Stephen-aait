<?php
/**
 * Store locator Image helper
 * 
 * @category    Clarion
 * @package     Clarion_Storelocator
 * @author      Clarion Magento Team
 */
class Clarion_Storelocator_Helper_Image extends Mage_Core_Helper_Abstract
{
    /**
     * Media path to extension imahes
     *
     * @var string
     */
    const MEDIA_PATH    = 'clarion_storelocator';

    /**
     * Maximum size for image in bytes
     * Default value is 1M
     *
     * @var int
     */
    const MAX_FILE_SIZE = 1048576;

    /**
     * Manimum image height in pixels
     *
     * @var int
     */
    const MIN_HEIGHT = 50;

    /**
     * Maximum image height in pixels
     *
     * @var int
     */
    const MAX_HEIGHT = 800;

    /**
     * Manimum image width in pixels
     *
     * @var int
     */
    const MIN_WIDTH = 50;

    /**
     * Maximum image width in pixels
     *
     * @var int
     */
    const MAX_WIDTH = 800;

    /**
     * Array of image size limitation
     *
     * @var array
     */
    protected $_imageSize   = array(
        'minheight'     => self::MIN_HEIGHT,
        'minwidth'      => self::MIN_WIDTH,
        'maxheight'     => self::MAX_HEIGHT,
        'maxwidth'      => self::MAX_WIDTH,
    );

    /**
     * Array of allowed file extensions
     *
     * @var array
     */
    protected $_allowedExtensions = array('jpg', 'gif', 'png','jpeg');

    /**
     * Return the base media directory for store images
     *
     * @return string
     */
    public function getBaseDir()
    {
        return Mage::getBaseDir('media') . DS . self::MEDIA_PATH;
    }

    /**
     * Return the Base URL for store images
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return Mage::getBaseUrl('media') . '/' . self::MEDIA_PATH;
    }

    /**
     * Remove store image by image filename
     *
     * @param string $imageFile
     * @return bool
     */
    public function removeImage($imageFile)
    {
        $io = new Varien_Io_File();
        $io->open(array('path' => $this->getBaseDir()));
        if ($io->fileExists($imageFile)) {
            return $io->rm($imageFile);
        }
        return false;
    }

    /**
     * Upload image and return uploaded image file name or false
     *
     * @throws Mage_Core_Exception
     * @param string $scope the request key for file
     * @return bool|string
     */
    public function uploadImage($scope)
    {
        $adapter  = new Zend_File_Transfer_Adapter_Http();
        $adapter->addValidator('ImageSize', true, $this->_imageSize);
        $adapter->addValidator('Size', true, self::MAX_FILE_SIZE);
        if ($adapter->isUploaded($scope)) {
            // validate image
            if (!$adapter->isValid($scope)) {
                Mage::throwException(Mage::helper('clarion_storelocator')->__('Uploaded image is not valid'));
            }
            $upload = new Varien_File_Uploader($scope);
            $upload->setAllowCreateFolders(true);
            $upload->setAllowedExtensions($this->_allowedExtensions);
            $upload->setAllowRenameFiles(true);
            $upload->setFilesDispersion(false);
            if ($upload->save($this->getBaseDir())) {
                return $upload->getUploadedFileName();
            }
        }
        return false;
    }

    /**
     * Return URL for resized store Image
     *
     * @param Clarion_Storelocator_Model_View $store
     * @param integer $width
     * @param integer $height
     * @return bool|string
     */
    public function resize(Clarion_Storelocator_Model_Storelocator $store, $width, $height = null)
    {
        if (!$store->getStoreLogo()) {
            return false;
        }

        if ($width < self::MIN_WIDTH || $width > self::MAX_WIDTH) {
            //return false;
        }
        $width = (int)$width;

        if (!is_null($height)) {
            if ($height < self::MIN_HEIGHT || $height > self::MAX_HEIGHT) {
                //return false;
            }
            $height = (int)$height;
        }

        $imageFile = $store->getStoreLogo();
        $cacheDir  = $this->getBaseDir() . DS . 'cache' . DS . $width;
        $cacheUrl  = $this->getBaseUrl() . '/' . 'cache' . '/' . $width . '/';

        $io = new Varien_Io_File();
        $io->checkAndCreateFolder($cacheDir);
        $io->open(array('path' => $cacheDir));
        if ($io->fileExists($imageFile)) {
            return $cacheUrl . $imageFile;
        }

        try {
            $image = new Varien_Image($this->getBaseDir() . DS . $imageFile);
            $image->resize($width, $height);
            $image->save($cacheDir . DS . $imageFile);
            return $cacheUrl . $imageFile;
        } catch (Exception $e) {
            Mage::logException($e);
            return false;
        }
    }

    /**
     * Removes folder with cached images
     *
     * @return boolean
     */
    public function flushImagesCache()
    {
        $cacheDir  = $this->getBaseDir() . DS . 'cache' . DS ;
        $io = new Varien_Io_File();
        if ($io->fileExists($cacheDir, false) ) {
            return $io->rmdir($cacheDir, true);
        }
        return true;
    }
}
