<?php

class Inky_Designersoftware_Helper_Resize extends Mage_Core_Helper_Abstract {

    var
            $width = null,
            $height = null,
            $rawImg = '',
            $img = false,
            $cacheDir = '',
            $cachedImage = '',
            $cachedImageUrl = '',
            $ext = '',
            $bgColor = array(255, 255, 255),
            $imageObj = '',
            $baseUrl = '',
            $placeHolder = false;

    public function init($img = false) {
        if ($img) {
            $this->rawImg = $img;
        }

        if (empty($this->placeHolder)) {
            $this->placeHolder = Mage::getDesign()->getSkinUrl() . 'images/catalog/product/placeholder/image.jpg';
        }
        $this->imagePath($this->rawImg);

        $this->imageObj = new Varien_Image($this->img);

        $path_parts = pathinfo($this->img);

        $this->ext = $path_parts['extension'];

        $this->cacheDir();

        return $this;
    }

    public function resize($width = false, $height = false) {
        if ($width) {
            $this->width = $width;
        }

        if ($height) {
            $this->height = $height;
        }

        $this->cacheIt();

        return $this->cachedImageUrl();
    }

    public function cachedImageUrl() {
        $img = str_replace(BP, '', $this->cachedImage);
        $img = trim(str_replace('\\', '/', $img), '/');

        return $this->baseUrl . $img;
    }

    public function cacheIt() {
        $this->cachedImage = $this->cacheDir . md5($this->img . $this->width . $this->height) . '.' . $this->ext;

        if (file_exists($this->cachedImage)) {
            return $this->cachedImage;
        }

        $this->resizer();
    }

    public function resizer() {
        try {
            $this->imageObj->constrainOnly(true);
            $this->imageObj->keepAspectRatio(true);
            $this->imageObj->keepFrame(true);
            $this->imageObj->keepTransparency(true);
            $this->imageObj->backgroundColor($this->bgColor);
            $this->imageObj->resize($this->width, $this->height);
            $this->imageObj->save($this->cachedImage);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function imagePath($img = '') {
        $this->baseUrl = str_replace('media/', '', Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA));
        $img = str_replace($this->baseUrl, '', $img);
        $img = trim(str_replace('/', DS, $img), DS);

        $this->img = BP . DS . $img;

        if ((!file_exists($this->img) || !is_file($this->img)) && !empty($this->placeHolder)) {
            $this->imagePath($this->placeHolder);
            $this->placeHolder = false;
        }
    }

    public function cacheDir() {
        $cache = BP . DS . 'media' . DS . 'catalog' . DS . 'cache' . DS;

        if (!is_dir($cache)) {
            mkdir($cache);
        }
        $this->cacheDir = $cache;
    }

}
