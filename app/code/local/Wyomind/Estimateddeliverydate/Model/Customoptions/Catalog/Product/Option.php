<?php

class Wyomind_Estimateddeliverydate_Model_Customoptions_Catalog_Product_Option extends MageWorx_CustomOptions_Model_Catalog_Product_Option {

   

    protected function _afterSave() {
        if (!Mage::helper('customoptions')->isEnabled() || Mage::app()->getRequest()->getControllerName() != 'catalog_product') {
            return parent::_afterSave();
        }

        $optionId = $this->getData('option_id');
        $defaultArray = $this->getData('default') ? $this->getData('default') : array();
        $tablePrefix = (string) Mage::getConfig()->getTablePrefix();
        $connection = Mage::getSingleton('core/resource')->getConnection('core_write');

        $helper = Mage::helper('customoptions');

        $storeId = $this->getProduct()->getStoreId();
        if (is_array($this->getData('values'))) {
            $values = array();
            foreach ($this->getData('values') as $key => $value) {
                if (isset($value['option_type_id'])) {

                    if (isset($value['dependent_ids']) && $value['dependent_ids'] != '') {
                        $dependentIds = array();
                        $dependentIdsTmp = explode(',', $value['dependent_ids']);
                        foreach ($dependentIdsTmp as $d_id) {
                            if ($this->decodeViewIGI($d_id) > 0)
                                $dependentIds[] = $this->decodeViewIGI($d_id);
                        }
                        $value['dependent_ids'] = implode(',', $dependentIds);
                    }

                    $value['sku'] = trim($value['sku']);

                    // prepare customoptions_qty
                    $customoptionsQty = '';
                    if (isset($value['customoptions_qty']) && $helper->getProductIdBySku($value['sku']) == 0) {
                        $customoptionsQty = strtolower(trim($value['customoptions_qty']));
                        if (substr($customoptionsQty, 0, 1) != 'x' && substr($customoptionsQty, 0, 1) != 'i' && substr($customoptionsQty, 0, 1) != 'l' && !is_numeric($customoptionsQty))
                            $customoptionsQty = '';
                        if (is_numeric($customoptionsQty))
                            $customoptionsQty = intval($customoptionsQty);
                        if (substr($customoptionsQty, 0, 1) == 'i')
                            $customoptionsQty = $this->decodeViewIGI($customoptionsQty);
                    }

                    $optionValue = array(
                        'option_id' => $optionId,
                        'sku' => $value['sku'],
                        'sort_order' => $value['sort_order'],
                        'leadtime' => $value['leadtime'],
                        'customoptions_qty' => $customoptionsQty,
                        'default' => array_search($key, $defaultArray) !== false ? 1 : 0,
                        'in_group_id' => $value['in_group_id']
                    );
                    if (isset($value['dependent_ids']))
                        $optionValue['dependent_ids'] = $value['dependent_ids'];
                    if (isset($value['weight']))
                        $optionValue['weight'] = $value['weight'];
                    if (isset($value['cost']))
                        $optionValue['cost'] = $value['cost'];
                    $optionValue['leadtime'] = $value['leadtime'];
                    $optionTypePriceId = 0;

                    if ($helper->isSkuNameLinkingEnabled() && (!isset($value['scope']['title']) && $value['scope']['title'] != 1) && (!isset($value['title']) || $value['title'] == '') && $value['sku']) {
                        $value['title'] = $helper->getProductNameBySku($value['sku'], $storeId);
                    }

                    if (isset($value['option_type_id']) && $value['option_type_id'] > 0) {
                        $optionTypeId = $value['option_type_id'];
                        if ($value['is_delete'] == '1') {
                            $connection->delete($tablePrefix . 'catalog_product_option_type_value', 'option_type_id = ' . $optionTypeId);
                            $helper->deleteOptionFile(null, $optionId, $optionTypeId);
                        } else {
                            $connection->update($tablePrefix . 'catalog_product_option_type_value', $optionValue, 'option_type_id = ' . $optionTypeId);

                            // update or insert price
                            if ($storeId > 0) {
                                $select = $connection->select()->from($tablePrefix . 'catalog_product_option_type_price', array('option_type_price_id'))->where('option_type_id = ' . $optionTypeId . ' AND `store_id` = ' . $storeId);
                                $optionTypePriceId = $isUpdate = $connection->fetchOne($select);
                            } else {
                                $isUpdate = 1;
                            }
                            if (isset($value['price']) && isset($value['price_type'])) {
                                $priceValue = array('price' => $value['price'], 'price_type' => $value['price_type']);
                                if ($isUpdate) {
                                    $connection->update($tablePrefix . 'catalog_product_option_type_price', $priceValue, 'option_type_id = ' . $optionTypeId . ' AND `store_id` = ' . $storeId);
                                } else {
                                    $priceValue['option_type_id'] = $optionTypeId;
                                    $priceValue['store_id'] = $storeId;
                                    $connection->insert($tablePrefix . 'catalog_product_option_type_price', $priceValue);
                                    $optionTypePriceId = $connection->lastInsertId($tablePrefix . 'catalog_product_option_type_price');
                                }
                            } elseif (isset($value['scope']['price']) && $value['scope']['price'] == 1 && $isUpdate && $storeId > 0) {
                                $connection->delete($tablePrefix . 'catalog_product_option_type_price', 'option_type_id = ' . $optionTypeId . ' AND `store_id` = ' . $storeId);
                                $optionTypePriceId = -1;
                            }

                            // update or insert title
                            if ($storeId > 0) {
                                $select = $connection->select()->from($tablePrefix . 'catalog_product_option_type_title', array('COUNT(*)'))->where('option_type_id = ' . $optionTypeId . ' AND `store_id` = ' . $storeId);
                                $isUpdate = $connection->fetchOne($select);
                            } else {
                                $isUpdate = 1;
                            }

                            if (isset($value['title'])) {
                                if ($isUpdate) {
                                    $connection->update($tablePrefix . 'catalog_product_option_type_title', array('title' => $value['title']), 'option_type_id = ' . $optionTypeId . ' AND `store_id` = ' . $storeId);
                                } else {
                                    $connection->insert($tablePrefix . 'catalog_product_option_type_title', array('option_type_id' => $optionTypeId, 'store_id' => $storeId, 'title' => $value['title']));
                                }
                            } elseif (isset($value['scope']['title']) && $value['scope']['title'] == 1 && $isUpdate && $storeId > 0) {
                                $connection->delete($tablePrefix . 'catalog_product_option_type_title', 'option_type_id = ' . $optionTypeId . ' AND `store_id` = ' . $storeId);
                            }
                        }
                    } else {
                        if ($value['is_delete'] == '1')
                            continue;
                        $connection->insert($tablePrefix . 'catalog_product_option_type_value', $optionValue);
                        $optionTypeId = $connection->lastInsertId($tablePrefix . 'catalog_product_option_type_value');
                        if (isset($value['price']) && isset($value['price_type'])) {
                            // save not default
                            //if ($storeId>0) $connection->insert($tablePrefix . 'catalog_product_option_type_price', array('option_type_id' =>$optionTypeId, 'store_id'=>$storeId, 'price' => $value['price'], 'price_type' => $value['price_type']));
                            // save default
                            $connection->insert($tablePrefix . 'catalog_product_option_type_price', array('option_type_id' => $optionTypeId, 'store_id' => 0, 'price' => $value['price'], 'price_type' => $value['price_type']));
                            $optionTypePriceId = $connection->lastInsertId($tablePrefix . 'catalog_product_option_type_price');
                        }
                        if (isset($value['title'])) {
                            // save not default
                            //if ($storeId>0) $connection->insert($tablePrefix . 'catalog_product_option_type_title', array('option_type_id' =>$optionTypeId, 'store_id'=>$storeId, 'title' => $value['title']));
                            // save default
                            $connection->insert($tablePrefix . 'catalog_product_option_type_title', array('option_type_id' => $optionTypeId, 'store_id' => 0, 'title' => $value['title']));
                        }
                    }

                    if ($optionTypeId > 0 && $optionTypePriceId >= 0) {
                        $id = $this->getData('id');

                        $this->_uploadImage('file_' . $id . '_' . $key, $optionId, $optionTypeId, $value);

                        // check $optionTypePriceId
                        if ($optionTypePriceId == 0) {
                            $select = $connection->select()->from($tablePrefix . 'catalog_product_option_type_price', array('option_type_price_id'))->where('option_type_id = ' . $optionTypeId . ' AND `store_id` = ' . $storeId);
                            $optionTypePriceId = $isUpdate = $connection->fetchOne($select);
                        }
                        if ($optionTypePriceId) {

                            // save special prices
                            if (isset($value['specials']) && is_array($value['specials'])) {
                                $specials = array();
                                foreach ($value['specials'] as $special) {
                                    if ($special['is_delete'] == '1' || isset($specials[$special['customer_group_id']])) {
                                        if ($special['special_price_id'] > 0)
                                            $connection->delete($tablePrefix . 'custom_options_option_type_special_price', 'option_type_special_price_id = ' . intval($special['special_price_id']));
                                        continue;
                                    }
                                    $specials[$special['customer_group_id']] = $special;
                                }
                                if (count($specials) > 0) {
                                    foreach ($specials as $special) {
                                        $zendDate = new Zend_Date();
                                        $dateFormat = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
                                        if ($special['date_from'])
                                            $special['date_from'] = $zendDate->setDate($special['date_from'], $dateFormat)->toString(Varien_Date::DATE_INTERNAL_FORMAT);
                                        else
                                            $special['date_from'] = null;
                                        if ($special['date_to'])
                                            $special['date_to'] = $zendDate->setDate($special['date_to'], $dateFormat)->toString(Varien_Date::DATE_INTERNAL_FORMAT);
                                        else
                                            $special['date_to'] = null;

                                        $specialData = array('option_type_price_id' => $optionTypePriceId,
                                            'customer_group_id' => $special['customer_group_id'],
                                            'price' => floatval($special['price']),
                                            'price_type' => $special['price_type'],
                                            'comment' => trim($special['comment']),
                                            'date_from' => $special['date_from'],
                                            'date_to' => $special['date_to'],
                                        );
                                        if ($special['special_price_id'] > 0) {
                                            $connection->update($tablePrefix . 'custom_options_option_type_special_price', $specialData, 'option_type_special_price_id = ' . intval($special['special_price_id']));
                                        } else {
                                            $connection->insert($tablePrefix . 'custom_options_option_type_special_price', $specialData);
                                        }
                                    }
                                }
                            }

                            // save tier prices
                            if (isset($value['tiers']) && is_array($value['tiers'])) {
                                $tiers = array();
                                foreach ($value['tiers'] as $tier) {
                                    $uniqKey = $tier['qty'] . '+' . $tier['customer_group_id'];
                                    if ($tier['is_delete'] == '1' || isset($tiers[$uniqKey])) {
                                        if ($tier['tier_price_id'] > 0)
                                            $connection->delete($tablePrefix . 'custom_options_option_type_tier_price', 'option_type_tier_price_id = ' . intval($tier['tier_price_id']));
                                        continue;
                                    }
                                    $tiers[$uniqKey] = $tier;
                                }
                                if (count($tiers) > 0) {
                                    foreach ($tiers as $tier) {
                                        $tierData = array('option_type_price_id' => $optionTypePriceId, 'customer_group_id' => $tier['customer_group_id'], 'qty' => intval($tier['qty']), 'price' => floatval($tier['price']), 'price_type' => $tier['price_type']);
                                        if ($tier['tier_price_id'] > 0) {
                                            $connection->update($tablePrefix . 'custom_options_option_type_tier_price', $tierData, 'option_type_tier_price_id = ' . intval($tier['tier_price_id']));
                                        } else {
                                            $connection->insert($tablePrefix . 'custom_options_option_type_tier_price', $tierData);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    unset($value['option_type_id']);
                }

                $values[$key] = $value;
            }
            $this->setData('values', $values);
        } elseif ($this->getGroupByType($this->getType()) == self::OPTION_GROUP_SELECT) {
            Mage::throwException(Mage::helper('catalog')->__('Select type options required values rows.'));
        }

        if (version_compare($helper->getMagetoVersion(), '1.4.0', '>='))
            $this->cleanModelCache();

        Mage::dispatchEvent('model_save_after', array('object' => $this));
        if (version_compare($helper->getMagetoVersion(), '1.4.0', '>='))
            Mage::dispatchEvent($this->_eventPrefix . '_save_after', $this->_getEventData());
        return $this;
    }

}
