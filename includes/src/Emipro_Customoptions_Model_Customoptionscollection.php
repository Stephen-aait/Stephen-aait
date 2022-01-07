<?php

ini_set("max_execution_time", 0);

class Emipro_Customoptions_Model_Customoptionscollection extends Mage_Core_Model_Abstract {

    public $tblcat;

    public function optionExist($optionId) {

        $sql = "select category_id from " . $this->getTbl() . " where option_id=" . $optionId;
        $results = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($sql);

        if (count($results) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getTbl() {
        $tblcat = Mage::getSingleton("core/resource")->getTableName("emiprotech_customoption_category");
        return $tblcat;
    }

    public function deleteCategory($sku, $deleteId) {
        $options = $sku;
        $categories = $deleteId;
        $catalogModel = Mage::getModel('catalog/product');
        foreach ($categories as $cat) {
            $catgoryModel = Mage::getModel('catalog/category')->load($cat);
            $products = $catgoryModel->getProductCollection();
            foreach ($products as $_product) {
                $opt = $catalogModel->load($_product->getId());
                foreach ($opt->getOptions() as $o) {
                    if ($o->getSku() == $options) {
                        $o->delete();
                    }
                }
                $sql = "select product_id from " . Mage::getConfig()->getTablePrefix() . "catalog_product_option where product_id=" . $opt->getId();
                $result = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($sql);
                if (count($result) <= 0) {
                    $opt->setHasOptions(0)->save();
                }
            }
        }
    }

    public function saveToCategory($sku, $category, $optionId) {
        $copyFrom = $sku;
        $categories = $category;
        $catalogModel = Mage::getModel('catalog/product');
        $assigned = array();
        $assigned[] = $copyFrom;
        $newOptions = array(); //array of custom options
        $i = 0;




        if (count($categories) < 1) {
            Mage::getSingleton('adminhtml/session')->addError('Select Categories to which you want to copy.');
        } else {
            if ($copyFrom == null) {
                Mage::getSingleton('adminhtml/session')->addError('Select Products from where you want to copy.');
            } else {
                $productId = $catalogModel->getIdBySku('customoptionmaster');
                $productCopyOptions = $catalogModel->load($productId);
                $opt = $productCopyOptions->getOptions();
                $newOptionsVal = array();


                foreach ($opt as $o) {
                    if ($o->getSku() == $copyFrom) {

                        $optionValues = $o->getValues();
                        $newOptions[$i]['title'] = $o->getTitle();
                        $newOptions[$i]['is_require'] = $o->getIsRequire();
                        $newOptions[$i]['type'] = $o->getType();
                        $newOptions[$i]['sort_order'] = $o->getSortOrder();

                        $newOptions[$i]['sku'] = $o->getSku();
                        $newOptions[$i]['max_characters'] = $o->getMaxCharacters();
                        $newOptions[$i]['file_extension'] = $o->getFileExtension();
                        $newOptions[$i]['image_size_x'] = $o->getImageSizeX();
                        $newOptions[$i]['image_size_y'] = $o->getImageSizeY();
                        $newOptions[$i]['default_title'] = $o->getDefaultTitle();
                        $newOptions[$i]['store_title'] = $o->getStoreTitle();
                        $newOptions[$i]['default_price'] = $o->getDefaultPrice();
                        $newOptions[$i]['default_price_type'] = $o->getDefaultPriceType();
                        $newOptions[$i]['store_price'] = $o->getStorePrice();
                        $newOptions[$i]['store_price_type'] = $o->getStorePriceType();
                        $newOptions[$i]['price'] = $o->getPrice();
                        $newOptions[$i]['price_type'] = $o->getPriceType();

                        $newOptionsVal = array();
                        foreach ($optionValues as $val) {

                            $copyValues = array();
                            $newOptionsValues = array(); //array of custom options values

                            $newOptionsValues['sku'] = $val->getSku();
                            $newOptionsValues['sort_order'] = $val->getSortOrder();
                            $newOptionsValues['default_title'] = $val->getDefaultTitle();
                            $newOptionsValues['store_title'] = $val->getStoreTitle();
                            $newOptionsValues['title'] = $val->getTitle();
                            $newOptionsValues['default_price'] = $val->getDefaultPrice();
                            $newOptionsValues['default_price_type'] = $val->getDefaultPriceType();
                            $newOptionsValues['store_price'] = $val->getStorePrice();
                            $newOptionsValues['store_price_type'] = $val->getStorePriceType();
                            $newOptionsValues['price'] = $val->getPrice();
                            $newOptionsValues['price_type'] = $val->getPriceType();

                            $newOptionsVal[] = ($newOptionsValues);
                        }
                        $newOptions[$i]['values'] = $newOptionsVal;  //custom option values
                        $i++;
                    }
                }
                foreach ($categories as $cat) {

                    $catgoryModel = Mage::getModel('catalog/category')->load($cat);
                    $productCollection = $catgoryModel->getProductCollection();
                    foreach ($productCollection as $cProduct) {
                        foreach ($newOptions as $_opt) {

                            $skus = array();
                            $option_product = Mage::getModel('catalog/product')->load($cProduct->getId());
                            $custom_options = $option_product->getOptions();
                            foreach ($custom_options as $custom_option) {
                                array_push($skus, $custom_option['sku']);
                            }
                            if (!in_array($_opt['sku'], $skus)) {

                                $option_product->setHasOptions(1)->save();
                                $opt = Mage::getModel('catalog/product_option');
                                $opt->setProduct($option_product);
                                $opt->addOption($_opt);
                                $opt->saveOptions(); //saving custom options
                                $option_product->save();
                            }
                        }
                    }
                }
            }
        }
    }

    public function deleteCustomOption($sku, $deleteId) {
        $options = $sku;
        $products = $deleteId;

        $catalogModel = Mage::getModel('catalog/product');
        $opt = $catalogModel->load($products);

        foreach ($opt->getOptions() as $o) {

            if ($o->getSku() == $options) {
                //echo $o->getSku();
                $o->delete();
            }
        }

        $sql = "select product_id from " . Mage::getConfig()->getTablePrefix() . "catalog_product_option where product_id=" . $opt->getId();
        $result = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($sql);
        if (count($result) <= 0) {
            $opt->setHasOptions(0)->save();
        }
    }

    public function saveCustomOption($optionSku, $productId) {

        $copyFrom = $optionSku;
        $copyTo = $productId;

        $catalogModel = Mage::getModel('catalog/product');
        if (count($copyTo) < 1) {
            Mage::getSingleton('adminhtml/session')->addError('Select Products to which you want to copy.');
            return;
        } else {
            if ($copyFrom == null) {
                Mage::getSingleton('adminhtml/session')->addError('Select Products Option which you want to copy.');
                return;
            } else {
                if ($copyFrom) {
                    $product = Mage::getModel('catalog/product');
                    $productId = $product->getIdBySku('customoptionmaster');
                    $product->load($productId);
                    $opt = $product->getOptions();
                    $newOptionsVal = array();


                    foreach ($opt as $o) {

                        if ($o->getSku() == $copyFrom) {
                            $optionValues = $o->getValues();
                            $newOptions = array(); //array of custom options
                            $newOptions['title'] = $o->getTitle();
                            $newOptions['is_require'] = $o->getIsRequire();
                            $newOptions['type'] = $o->getType();
                            $newOptions['sort_order'] = $o->getSortOrder();
                            $newOptions ['sku'] = $o->getSku();
                            $newOptions ['max_characters'] = $o->getMaxCharacters();
                            $newOptions ['file_extension'] = $o->getFileExtension();
                            $newOptions ['image_size_x'] = $o->getImageSizeX();
                            $newOptions ['image_size_y'] = $o->getImageSizeY();
                            $newOptions ['default_title'] = $o->getDefaultTitle();
                            $newOptions ['store_title'] = $o->getStoreTitle();
                            $newOptions ['default_price'] = $o->getDefaultPrice();
                            $newOptions ['default_price_type'] = $o->getDefaultPriceType();
                            $newOptions ['store_price'] = $o->getStorePrice();
                            $newOptions ['store_price_type'] = $o->getStorePriceType();
                            $newOptions ['price'] = $o->getPrice();
                            $newOptions ['price_type'] = $o->getPriceType();

                            $newOptionsVal = array();
                            foreach ($optionValues as $val) {
                                $copyValues = array();
                                $newOptionsValues = array(); //array of custom options values

                                $newOptionsValues['sku'] = $val->getSku();
                                $newOptionsValues['sort_order'] = $val->getSortOrder();
                                $newOptionsValues['default_title'] = $val->getDefaultTitle();
                                $newOptionsValues['store_title'] = $val->getStoreTitle();
                                $newOptionsValues['title'] = $val->getTitle();
                                $newOptionsValues['default_price'] = $val->getDefaultPrice();
                                $newOptionsValues['default_price_type'] = $val->getDefaultPriceType();
                                $newOptionsValues['store_price'] = $val->getStorePrice();
                                $newOptionsValues['store_price_type'] = $val->getStorePriceType();
                                $newOptionsValues['price'] = $val->getPrice();
                                $newOptionsValues['price_type'] = $val->getPriceType();

                                $newOptionsVal[] = ($newOptionsValues);
                            }
                            $newOptions['values'] = $newOptionsVal;  //custom option values 									


                            $skus = array();
                            $option_product = Mage::getModel('catalog/product')->load($copyTo);
                            $custom_options = $option_product->getOptions();


                            foreach ($custom_options as $custom_option) {
                                array_push($skus, $custom_option['sku']);
                            }

                            if (!in_array($newOptions['sku'], $skus)) {

                                $option_product->setHasOptions(1)->save();
                                $opt = Mage::getModel('catalog/product_option');
                                $opt->setProduct($option_product);
                                $opt->addOption($newOptions);
                                $opt->saveOptions(); //saving custom options
                                $option_product->save();
                            }
                        }
                    }
                }
            }
        }
    }

}
