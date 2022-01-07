<?php

class Wyomind_Estimateddeliverydate_Model_Observer {

    /**
     * Flag to stop observer executing more than once
     *
     * @var static bool
     */
    static protected $_singletonFlag = false;

    public function saveConfig($observer) {
        $attributes = explode(",", Mage::getStoreConfig("estimateddeliverydate/leadtimes_settings/base_attribute"));
        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('core_read');
        $write = $resource->getConnection('core_write');
        $tableEal = Mage::getSingleton("core/resource")->getTableName("eav_attribute_leadtime");
        $sql = array();
        $value_ids = array();
        foreach ($attributes as $attribute) {


            $attribute = Mage::getModel('catalog/resource_eav_attribute')->load($attribute);
            $values = $attribute->getSource()->getAllOptions();
            foreach ($values as $value) {

                if ((string) $value["value"] != '') {
                    $value_ids[] = $value['value'];
                    $sql[] = "SELECT @value:=IF(COUNT(value)<1,0,value) FROM $tableEal WHERE attribute_id=" . $attribute['attribute_id'] . " AND value_id=" . $value['value'] . ";";
                    $sql[] = "REPLACE INTO $tableEal (attribute_id,value_id,value) VALUES (" . $attribute['attribute_id'] . "," . $value['value'] . ",@value);";
                }
            }
        }
        $sql[] = "SELECT NULL;";
        $sql[] = "DELETE FROM $tableEal WHERE attribute_id NOT IN ('" . implode("','", $attributes) . "')";
        $sql[] = "SELECT NULL;";
        $sql[] = "DELETE FROM $tableEal WHERE value_id NOT IN ('" . implode("','", $value_ids) . "')";
        $commit = true;


        foreach ($sql as $k => $s) {

            try {
                if ($k % 2 == 0) {
                    $read->fetchAll($s);
                } else {
                    $write->exec($s);
                }
            } catch (Mage_Core_Exception $e) {
                $commit = false;
            } catch (Exception $e) {

                $commit = false;
            }
        }
        if (!$commit) {
            Mage::getSingleton("core/session")->addError(Mage::helper("estimateddeliverydate")->__("Error while processing. Rollback happened."));
            $write->rollback();
            return false;
        } else {
            $write->commit();
            Mage::getSingleton("core/session")->addSuccess(Mage::helper("estimateddeliverydate")->__("Leadtime/Attribute updated. Go to <a href='" . Mage::helper("adminhtml")->getUrl("adminhtml/manageleadtimes/index/") . "'>Catalog > Attributes > Manage Leadtime/Attribute</a>."));
        }



        /*  Liste des  attributs disponible dans la bdd */
    }

    public function saveProductTabData(Varien_Event_Observer $observer) {

        if (!self::$_singletonFlag) {
            self::$_singletonFlag = true;
            Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
            $product = $observer->getEvent()->getProduct();
            
            try {

                $fields = Mage::helper("estimateddeliverydate")->getConfig();
                $data = $this->_getRequest()->getPost();
               
                foreach ($fields as $type => $group) {
                    foreach ($group['fields'] as $name => $field) {

                        $storeId = Mage::app()->getRequest()->getParam('store');


                        ((@$data['product'][$type . "_" . $name])) ? $value = $data['product'][$type . "_" . $name] : $value = null;
                        ((@$data['product']["use_config_" . $type . "_" . $name])) ? $useConfigValue = $data['product']["use_config_" . $type . "_" . $name] : $useConfigValue = 0;
                        ((@$data['product']["use_base_" . $type . "_" . $name])) ? $useBaseValue = $data['product']["use_base_" . $type . "_" . $name] : $useBaseValue = 0;

                        if ($storeId != Mage_Core_Model_App::ADMIN_STORE_ID) {
                            $product->setStoreId($storeId);
                        }


                        if ($useConfigValue != 1) {
                            $useConfigValue = 0;
                        }
                        if ($useBaseValue != 1 && ($storeId != Mage_Core_Model_App::ADMIN_STORE_ID)) {
                            $useBaseValue = 0;
                        }

                        $product->setData($type . "_" . $name, $value);

                        $product->setData("use_config_" . $type . "_" . $name, $useConfigValue);
                        if ($storeId != Mage_Core_Model_App::ADMIN_STORE_ID) {
                            $product->setData("use_base_" . $type . "_" . $name, $useBaseValue);
                        }
                    }
                }
                
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
    }

    /**
     * Retrieve the product model
     *
     * @return Mage_Catalog_Model_Product $product
     */
    public function getProduct() {
        return Mage::registry('product');
    }

    /**
     * Shortcut to getRequest
     *
     */
    protected function _getRequest() {
        return Mage::app()->getRequest();
    }

    public function addComment($observer) {
        $order = $observer->getEvent()->getOrder();
        $order->addStatusHistoryComment(strip_tags(Mage::helper("estimateddeliverydate/data")->getCartMessage()), false);
        $this->orderUpdate($observer);
    }

    public function orderUpdate($observer) {
        $order = $observer->getEvent()->getOrder();
        $order->setEstimatedDeliveryDate(Mage::helper("estimateddeliverydate/data")->getEstimatedDeliveryDate($order));
        $order->save();
    }

}
