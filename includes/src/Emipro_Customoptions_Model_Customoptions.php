<?php
class Emipro_Customoptions_Model_Customoptions extends Mage_Core_Model_Abstract {

    public function _construct() {
        $this->_init('emipro_customoptions/customoptions');
    }
    public function saveCustomOptionBySku($sku,$productId)
    {
		$customoption=Mage::getModel("emipro_customoptions/product_option")->load($sku,"sku");
		$this->saveCustomOption($customoption->getId(),array($productId));

	}
    public function saveCustomOption($data,$productsId)
    {			
		$model1=Mage::getModel("emipro_customoptions/product_option")->load($data);
        $newOptionsVal = array();
           $optionsArray=array();

						foreach ($model1->getOption() as $o) {
                            $optionValues = $model1->getOptionValues();			
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
                            $newOptions['values'] = $newOptionsVal;                          
						}
			try
			{
				foreach($productsId as $productId)
				{
					$skus = array();
					$option_product = Mage::getModel('catalog/product')->load($productId);
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
			catch(Exception $e)
			{
				echo $e->getMessage();
			}
	}
	public function deleteCustomOption($sku, $deleteId) {
        $options = $sku;
        $products = $deleteId;

        $catalogModel = Mage::getModel('catalog/product');
        $opt = $catalogModel->load($products);

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
	public function deleteCategory($sku, $deleteId) {
        $options = $sku;
        $categories = $deleteId;
		foreach ($categories as $cat) {
            $catgoryModel = Mage::getModel('catalog/category')->load($cat);
            $products = $catgoryModel->getProductCollection();
            foreach ($products as $_product) {
				$catalogModel = Mage::getModel('catalog/product');
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

    public function saveToCategory($data, $category, $optionId) {
		
		$model1=Mage::getModel("emipro_customoptions/product_option")->load($data);
           $newOptionsVal = array();
           $optionsArray=array();

						foreach ($model1->getOption() as $o) {
                            $optionValues = $model1->getOptionValues();			
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
                            $newOptions['values'] = $newOptionsVal;
						}
			$categories = $category;
			$catalogModel = Mage::getModel('catalog/product');
			$assigned = array();
			$i = 0;
			if (count($categories) < 1) {
				Mage::getSingleton('adminhtml/session')->addError('Select Categories to which you want to copy.');
			} else {
                foreach ($categories as $cat) {
                    $catgoryModel = Mage::getModel('catalog/category')->load($cat);
                    $productCollection = $catgoryModel->getProductCollection();
                    foreach ($productCollection as $cProduct) {
                            $skus = array();
                            $option_product = Mage::getModel('catalog/product')->load($cProduct->getId());
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
