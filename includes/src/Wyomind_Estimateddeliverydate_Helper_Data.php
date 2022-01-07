<?php

class Wyomind_Estimateddeliverydate_Helper_Data extends Mage_Core_Helper_Abstract {

    public $_debug = false;

    public function __construct() {
        if (!$this->_debug) {

            if (isset($_GET['edd'])) {

                $this->_debug = true;
            }
        }
    }

    Public function addTrace($trace) {
        if ($this->_debug) {
            echo "<pre>";
            if (is_array($trace))
                var_dump($trace);
            else
                echo $trace . "<br>";
            echo "</pre>";
        }
    }

    public function getLeadtime($_product, $storeId, $type, $additionalFrom = 0,
            $additionalTo = 0, $customCutoff = false) {


        $today = Mage::getSingleton('core/date')->gmtTimestamp(Mage::getSingleton('core/date')->date("Y-m-d 00:00:00"));
        $currentTimestamp = Mage::getSingleton('core/date')->gmtTimestamp();

        // Get start date
        $backToStockIn = 0;
        if ($_product->getBackToStock() != "" && $type == "backorders") {
            $this->addTrace('-------- Back in stock --------');
            $this->addTrace($_product->getBackToStock());
            $currentGmtTime = Mage::getSingleton('core/date')->gmtTimestamp(strtotime($_product->getBackToStock()));
            $backToStockIn = (Mage::getSingleton('core/date')->gmtTimestamp(strtotime($_product->getBackToStock())) - $today) / 86400;
            $this->addTrace("Back in stock +" . $backToStockIn);
        } else {
            $currentGmtTime = $currentTimestamp;
        }




        $this->addTrace('-------- Product availability --------');
        $this->addTrace($type);


        // get the configuration
        $fields = Mage::helper("estimateddeliverydate")->getConfig();


        // collect the values for the leadtime (order or backorder)
        $data = $this->getData($_product->getId(), $type, "leadtime", $fields[$type]["fields"]['leadtime']['config'], $storeId);
        $this->addTrace('-------- Leadtime data --------');
        $this->addTrace(($data));


        // Get the calculated leadtime
        $v = explode('-', $data['leadtime']);
        $leadtime = array("from" => $v[0], "to" => @$v[1]);
        $this->addTrace('-------- From ... To ... --------');
        $this->addTrace(($leadtime));

        /* Add the additional leadtimes for custom options */
        $leadtime["from"]+=$additionalFrom;
        $leadtime["to"]+=$additionalTo;
        $this->addTrace('-------- Custom options additional delay --------');
        $this->addTrace(($leadtime));

        /* Add the additional leadtimes for attributes */
        if (Mage::getStoreConfig("estimateddeliverydate/leadtimes_settings/use_base_attribute", $storeId)) {
            $additional = array("from" => 0, "to" => 0);
            foreach (explode(',', Mage::getStoreConfig("estimateddeliverydate/leadtimes_settings/base_attribute", $storeId))as $attribute) {

                $option_id = Mage::getModel("catalog/product")->load($_product->getId())->getData(Mage::getModel("eav/entity_attribute")->load($attribute)->getAttributeCode());

                if ($option_id) {
                    $data = Mage::getModel("estimateddeliverydate/attributes")->getCollection()
                            ->addFieldToFilter('attribute_id', $attribute)
                            ->addFieldToFilter('value_id', $option_id);

                    foreach ($data as $d) {

                        $v = explode('-', $d->getValue());

                        $additional["from"]+= $v[0];
                        if (isset($v[1])) {
                            $additional["to"]+= $v[1];
                        }
                    }
                }
            }
            $this->addTrace('-------- Attributes additional delay --------');
            $this->addTrace(($leadtime));

            switch (Mage::getStoreConfig("estimateddeliverydate/leadtimes_settings/base_attribute_scope", $storeId)) {
                case 1:
                    $leadtime["from"]+=$additional["from"];
                    $leadtime["to"]+=$additional["to"];
                    break;
                case 2:
                    if ($type == "orders") {
                        $leadtime["from"]+=$additional["from"];
                        $leadtime["to"]+=$additional["to"];
                    }
                    break;
                case 3:

                    if ($type == "backorders") {

                        $leadtime["from"]+=$additional["from"];
                        $leadtime["to"]+=$additional["to"];
                    }
                    break;
            }
        }


        if (!$customCutoff) {
            $cutOff = $this->getData($_product->getId(), $type, "cutoff", $fields[$type]["fields"]['cutoff']['config'], $storeId);
            $cutOff = $cutOff["cutoff"];
        } else
            $cutOff = $customCutoff;
        $daysoff = array_map('trim', explode("\n", Mage::getStoreConfig("estimateddeliverydate/leadtimes_settings/holidays", $storeId)));

        $data = $this->getData($_product->getId(), $type, "shipping", $fields[$type]["fields"]['shipping']['config'], $storeId);
        $shippingDays = explode(',', $data['shipping']);

        if ($_product->getBackToStock() != "" && $type == "backorders") {
            $delay['from'] = 0;
            $delay['to'] = 0;
        } else {
            $delay['from'] = $leadtime['from'];
            $delay['to'] = $leadtime['to'];
        }

        $this->addTrace('-------- Shipping delay --------');
        $x = 0;
        $co = 0;
        if (Mage::getSingleton('core/date')->date("H,i,s", $currentGmtTime) > $cutOff) {
            $delay["from"] ++;
            $delay["to"] ++;
            $x++;
            $co++;
            $this->addTrace("After $cutOff +1");
        }
        $readyToShop = false;
        for ($i = $x; $i <= $delay["from"]; $i++) {
            if (!in_array(Mage::getSingleton('core/date')->date("w", $currentGmtTime + 86400 * $i), $shippingDays) || in_array(Mage::getSingleton('core/date')->date("Y-m-d", $currentGmtTime + 86400 * $i), $daysoff)) {
                $delay["from"] ++;
                if (!$readyToShop)
                    $co++;


                if (in_array(Mage::getSingleton('core/date')->date("Y-m-d", $currentGmtTime + 86400 * $i), $daysoff)) {
                    $msg = "DAY OFF! No Shipping on : " . Mage::getSingleton('core/date')->date("Y-m-d", $currentGmtTime + 86400 * $i);
                } else {
                    $msg = "No shipping on " . Mage::getSingleton('core/date')->date("l", $currentGmtTime + 86400 * $i);
                }
                $msg.= " +1";
                $this->addTrace($msg);
            } else {
                $readyToShop = true;
            }
        }

        $this->addTrace("Total leadtime from " . $delay['from'] . "</b>");

        for ($i = $x; $i <= $delay["to"]; $i++) {

            if (!in_array(Mage::getSingleton('core/date')->date("w", $currentGmtTime + 86400 * $i), $shippingDays) || in_array(Mage::getSingleton('core/date')->date("Y-m-d", $currentGmtTime + 86400 * $i), $daysoff)) {
                $delay["to"] ++;



                if (in_array(Mage::getSingleton('core/date')->date("Y-m-d", $currentGmtTime + 86400 * $i), $daysoff)) {
                    $msg = "DAY OFF! No Shipping on : " . Mage::getSingleton('core/date')->date("Y-m-d", $currentGmtTime + 86400 * $i);
                } else {
                    $msg = "No shipping on " . Mage::getSingleton('core/date')->date("l", $currentGmtTime + 86400 * $i);
                }
                $msg.= " +1<br>";
                $this->addTrace($msg);
            }
        }
        $this->addTrace("Total leadtime to " . $delay['to'] . "</b>");





        $leadtime["from"] = $delay['from'];
        $leadtime["to"] = $delay['to'];

        $cutOff = explode(',', $cutOff);
        if ($_product->getBackToStock() != "" && $type == "backorders") {
            $cutOffTime = $currentGmtTime + $cutOff[0] * 3600 + $cutOff[1] * 60 + $cutOff[2] + 86400 * $co;
            $countdown = $cutOffTime - $currentTimestamp;
        } else {
            $cutOffTime = $today + $cutOff[0] * 3600 + $cutOff[1] * 60 + $cutOff[2] + 86400 * $co;
            $countdown = $cutOffTime - $currentGmtTime;
        }


        $leadtime['back_in_stock'] = $backToStockIn;
        $leadtime["countdown"] = $countdown = "<span class='edd_countdown' countdown='" . abs($countdown) . "'></span>";

        return $leadtime;
    }

    public function getProductMessage($_product, $storeId, $additionalFrom = 0,
            $additionalTo = 0, $customMessage = false, $customCutoff = false) {
        $inventory = Mage::getModel('cataloginventory/stock_item')->loadByProduct($_product);
        $manage_stock = $inventory->getManageStock() || ($inventory->getUseConfigManageStock() && Mage::getStoreConfig("cataloginventory/item_options/manage_stock", $storeId));
        $type = ((int) $inventory->getQty() <= 0 && $manage_stock) ? "backorders" : "orders";

        if ($_product->isAvailable()) {
            $fields = Mage::helper("estimateddeliverydate")->getConfig();
            $dateFormat = Mage::getStoreConfig("estimateddeliverydate/display_settings/date_format", $storeId);
            $leadtime = $this->getLeadtime($_product, $storeId, $type, $additionalFrom, $additionalTo, $customCutoff);
            if (!$customMessage)
                $message = $this->getData($_product->getId(), $type, "message", $fields[$type]["fields"]['message']['config'], $storeId);
            else
                $message = array('message' => $customMessage);
            $this->addTrace(($message));

            // Get start date
            if ($_product->getBackToStock() && $type == "backorders") {

                $currentGmtTime = Mage::getSingleton('core/date')->gmtTimestamp(strtotime($_product->getBackToStock()));
            } else {
                $currentGmtTime = Mage::getSingleton('core/date')->gmtTimestamp();
            }

            $date['from'] = Mage::getSingleton('core/date')->date($dateFormat, $currentGmtTime + 86400 * $leadtime["from"]);
            $date['from'] = $this->dateTranslate($date['from']);
            $date['to'] = Mage::getSingleton('core/date')->date($dateFormat, $currentGmtTime + 86400 * $leadtime["to"]);
            $date['to'] = $this->dateTranslate($date['to']);
            $week['from'] = floor($leadtime['from'] / 7);
            $week['to'] = ceil($leadtime['to'] / 7);

            return str_replace(array("{{leadtime_from}}", "{{leadtime_to}}", "{{date_from}}", "{{date_to}}", "{{week_from}}", "{{week_to}}", "{{countdown}}"), array($leadtime['from'] + $leadtime['back_in_stock'], $leadtime['to'] + $leadtime['back_in_stock'], $date['from'], $date['to'], $week['from'], $week['to'], $leadtime['countdown']), $message['message']);
        }
    }

    public function getProductInCartMessage($_product, $backorderQty) {


        if ($_product->isAvailable() && Mage::getStoreConfig("estimateddeliverydate/display_settings/use_message_for_cart_item")) {
            $type = "orders";
            $m = "";
            if ($backorderQty > 0) {
                $type = "backorders";
                $m = "_bo";
            }

            return "[[" . $type . "||" . $m . "||" . $_product->getId() . "]]";
        } else
            return null;

        return null;
    }

    public function parseMessage($data, $options) {


        @list($type, $m, $id) = explode("||", str_replace(array("[[", "]]"), "", $data));
        $storeId = Mage::app()->getRequest()->getParam('store');
        $helper = Mage::helper('catalog/product_configuration');
        $method = Mage::getStoreConfig("estimateddeliverydate/custom_options/calculation", $storeId);
        $_product = Mage::getModel("catalog/product")->load($id);
        if ($_product->isAvailable() && Mage::getStoreConfig("estimateddeliverydate/display_settings/use_message_for_cart_item")) {
            $storeId = Mage::app()->getRequest()->getParam('store');
            $message = Mage::getStoreConfig("estimateddeliverydate/display_settings/message_for_cart_item" . $m, $storeId);
            $dateFormat = Mage::getStoreConfig("estimateddeliverydate/display_settings/date_format", $storeId);

            $leadtimes = array();

            foreach ($options as $option) {
                foreach (explode(",", @$option['value_id']) as $value_id)
                    $leadtimes[] = explode(",", Mage::getModel('catalog/product_option_value')->load($value_id)->getData("leadtime"));
            }
            $from = 0;
            $to = 0;
            foreach ($leadtimes as $ld) {
                if ($method) {
                    $from+=$ld[0];
                    if (isset($ld[1]))
                        $to+=$ld[1];
                } else {
                    if ($ld[0] > $from) {
                        $from = $ld[0];
                    }
                    if (isset($ld[1]))
                        if ($ld[1] > $to) {
                            $to = $ld[1];
                        }
                }
            }


            $leadtime = $this->getLeadtime($_product, $storeId, $type, $from, $to);
            // Get start date
            if ($_product->getBackToStock() && $type == "backorders") {

                $currentGmtTime = Mage::getSingleton('core/date')->gmtTimestamp(strtotime($_product->getBackToStock()));
            } else {
                $currentGmtTime = Mage::getSingleton('core/date')->gmtTimestamp();
            }
            $date['from'] = Mage::getSingleton('core/date')->date($dateFormat, $currentGmtTime + 86400 * $leadtime["from"]);
            $date['from'] = $this->dateTranslate($date['from']);
            $date['to'] = Mage::getSingleton('core/date')->date($dateFormat, $currentGmtTime + 86400 * $leadtime["to"]);
            $date['to'] = $this->dateTranslate($date['to']);

            if (isset($_GET['edd'])) {
                echo "<br><br>----------------OUPUT----------------<br>";
            }
            return str_replace(array("{{leadtime_from}}", "{{leadtime_to}}", "{{date_from}}", "{{date_to}}"), array($leadtime['from'] + $leadtime['back_in_stock'], $leadtime['to'] + $leadtime['back_in_stock'], $date['from'], $date['to']), $message);
        } else {
            return "";
        }
    }

    function cmp_from($a, $b) {
        if ($a['from'] > $b['from']) {
            return 1;
        } elseif ($a['from'] < $b['from']) {
            return -1;
        } else {
            return 0;
        }
    }

    function cmp_to($a, $b) {
        if ($a['to'] > $b['to']) {
            return 1;
        } elseif ($a['to'] < $b['to']) {
            return -1;
        } else {
            return 0;
        }
    }

    public function getCartMessage() {

        $session = Mage::getSingleton('checkout/session');
        $storeId = Mage::app()->getRequest()->getParam('store');
        $message = Mage::getStoreConfig("estimateddeliverydate/display_settings/message_in_cart", $storeId);
        $dateFormat = Mage::getStoreConfig("estimateddeliverydate/display_settings/date_format", $storeId);
        $items = $session->getQuote()->getAllItems();

        $helper = Mage::helper('catalog/product_configuration');
        $method = Mage::getStoreConfig("estimateddeliverydate/custom_options/calculation", $storeId);

        if (count($items)) {
            foreach ($items as $item) {

                $_product = Mage::getModel('catalog/product')->load($item->getProductId());
                if ($_product->getTypeId() == "configurable") {
                    continue;
                }
                $this->addTrace("......................" . $item->getName() . "......................");

                $leadtimes = array();

                foreach ($helper->getCustomOptions($item) as $option) {
                    foreach (explode(",", $option['value_id']) as $value_id)
                        $leadtimes[] = explode(",", Mage::getModel('catalog/product_option_value')->load($value_id)->getData("leadtime"));
                }
                $from = 0;
                $to = 0;
                foreach ($leadtimes as $ld) {
                    if ($method) {
                        $from+=$ld[0];
                        if (isset($ld[1]))
                            $to+=$ld[1];
                    } else {
                        if ($ld[0] > $from) {
                            $from = $ld[0];
                        }
                        if (isset($ld[1])) {
                            if ($ld[1] > $to) {
                                if (isset($ld[1]))
                                    $to = $ld[1];
                            }
                        }
                    }
                }


                $inventory = Mage::getModel('cataloginventory/stock_item')->loadByProduct($_product);

                $type = ((int) $inventory->getQty() - $item->getQty() < 0 && ($inventory->getBackorders() == Mage_CatalogInventory_Model_Stock::BACKORDERS_YES_NOTIFY || $inventory->getBackorders() == Mage_CatalogInventory_Model_Stock::BACKORDERS_YES_NONOTIFY)) ? "backorders" : "orders";
                $leadtime[] = $this->getLeadtime($_product, $storeId, $type, $from, $to);
            }
            usort($leadtime, array("Wyomind_Estimateddeliverydate_Helper_Data", "cmp_from"));
            $from = $leadtime;
            $final_leadtime = array_pop($from);

            usort($leadtime, array("Wyomind_Estimateddeliverydate_Helper_Data", "cmp_to"));
            $to = array_pop($leadtime);

            $final_leadtime['to'] = $to['to'];

            if ($_product->getBackToStock() && $type == "backorders") {

                $currentGmtTime = Mage::getSingleton('core/date')->gmtTimestamp(strtotime($_product->getBackToStock()));
            } else {
                $currentGmtTime = Mage::getSingleton('core/date')->gmtTimestamp();
            }

            $date['from'] = Mage::getSingleton('core/date')->date($dateFormat, $currentGmtTime + 86400 * ($final_leadtime['from']));
            $date['from'] = $this->dateTranslate($date['from']);
            $date['to'] = Mage::getSingleton('core/date')->date($dateFormat, $currentGmtTime + 86400 * ($final_leadtime['to']));
            $date['to'] = $this->dateTranslate($date['to']);
            $week['from'] = floor($final_leadtime['from'] / 7);
            $week['to'] = ceil($final_leadtime['to'] / 7);
            return "<ul class='messages'><li class='success-msg'><ul><li><span>" . str_replace(array("{{leadtime_from}}", "{{leadtime_to}}", "{{date_from}}", "{{date_to}}", "{{week_from}}", "{{week_to}}"), array($final_leadtime['from'] + $final_leadtime['back_in_stock'], $final_leadtime['to'] + $final_leadtime['back_in_stock'], $date['from'], $date['to'], $week['from'], $week['to']), $message) . "</span></li></ul></li></ul>";
        }
    }

    public function dateTranslate($date) {

        $longDays = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
        $longDaysLocale = array($this->__('Monday'), $this->__('Tuesday'), $this->__('Wednesday'), $this->__('Thursday'), $this->__('Friday'), $this->__('Saturday'), $this->__('Sunday'));

        $shortDays = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
        $shortDaysLocale = array($this->__('Mon'), $this->__('Tue'), $this->__('Wed'), $this->__('Thu'), $this->__('Fri'), $this->__('Sat'), $this->__('Sun'));

        $months = array("January", 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        $monthsLocale = array($this->__('January'), $this->__('February'), $this->__('March'), $this->__('April'), $this->__('May'), $this->__('June'), $this->__('July'), $this->__('August'), $this->__('September'), $this->__('October'), $this->__('November'), $this->__('December'));

        $ret = str_replace($longDays, $longDaysLocale, $date);
        $ret = str_replace($shortDays, $shortDaysLocale, $ret);
        return str_replace($months, $monthsLocale, $ret);
    }

    public function getConfig() {
        return array(
            "orders" => array(
                "label" => "In stock items",
                "fields" => array(
                    "leadtime" => array(
                        "label" => "Leadtime",
                        "config" => "leadtimes_settings/default_orders_leadtime",
                        "type" => "text",
                        "visible" => true,
                    ),
                    "message" => array(
                        "label" => "Message to display in product page",
                        "config" => "display_settings/message_in_product_page",
                        "type" => "text",
                        "visible" => true,
                    ),
                    "shipping" => array(
                        "label" => "Shipping days",
                        "config" => "leadtimes_settings/order_shipping_days",
                        "type" => "select",
                        "values" => new Wyomind_Estimateddeliverydate_Model_System_Config_Source_Days,
                        "visible" => false,
                    ),
                    "cutoff" => array(
                        "label" => "Last shipping time",
                        "config" => "leadtimes_settings/order_last_shipping_time",
                        "type" => "time",
                        "visible" => true,
                    ),
                ),
            ),
            "backorders" => array(
                "label" => "Backordered items",
                "fields" => array(
                    "leadtime" => array(
                        "label" => "Leadtime",
                        "config" => "leadtimes_settings_bo/default_backorders_leadtime",
                        "type" => "text",
                        "visible" => true,
                    ),
                    "message" => array(
                        "label" => "Message to display in product page",
                        "config" => "display_settings/message_in_product_page_bo",
                        "type" => "text",
                        "visible" => true,
                    ),
                    "shipping" => array(
                        "label" => "Shipping days",
                        "config" => "leadtimes_settings_bo/backorder_shipping_days",
                        "type" => "select",
                        "values" => new Wyomind_Estimateddeliverydate_Model_System_Config_Source_Days,
                        "visible" => false,
                    ),
                    "cutoff" => array(
                        "label" => "Last shipping time",
                        "config" => "leadtimes_settings_bo/backorder_last_shipping_time",
                        "type" => "time",
                        "visible" => true,
                    ),
                ),
            ),
        );
    }

    public function getData($productId, $type, $name, $configPath, $storeId = 0) {


        $product = array();
        $productBase = Mage::getModel('catalog/product')->setStoreId(0)->load($productId);

        $productStore = Mage::getModel('catalog/product')->setStoreId($storeId)->load($productId);

// config value
        $product['config'][$name] = Mage::getStoreConfig("estimateddeliverydate/" . $configPath, $storeId);

// default values
        $product['base'][$name] = $productBase->getData($type . "_" . $name);

        if (is_null($product['base'][$name])) {
            $product['base'][$name] = $product['config'][$name];
        }

        $product[$name] = $product['base'][$name];

        $product['base']['use_config_' . $name] = $productBase->getData("use_config_" . $type . "_" . $name);
        if (is_null($product['base']['use_config_' . $name])) {
            $product['base']['use_config_' . $name] = 1;
        }

        $product["use_config_" . $name] = $product['base']['use_config_' . $name];

        if ($product['base']['use_config_' . $name]) {
            $product[$name] = $product['config'][$name];
            $product['base'][$name] = $product['config'][$name];
        }


// store view values
        if ($storeId != Mage_Core_Model_App::ADMIN_STORE_ID) {
            $product['store'][$name] = $productStore->getData($type . "_" . $name);
            if (is_null($product['store'][$name])) {
                $product['store'][$name] = $product['config'][$name];
            }

            $product[$name] = $product['store'][$name];

            $product['store']['use_config_' . $name] = $productStore->getData("use_config_" . $type . "_" . $name);
            if (is_null($product['store']['use_config_' . $name])) {
                $product['store']['use_config_' . $name] = 1;
            }

            $product["use_config_" . $name] = $product['store']['use_config_' . $name];
            if ($product['store']['use_config_' . $name]) {
                $product[$name] = $product['config'][$name];
                $product['store'][$name] = $product['config'][$name];
            }

            $product['store']['use_base_' . $name] = $productStore->getData("use_base_" . $type . "_" . $name);

            if (is_null($product['store']['use_base_' . $name])) {
                $product['store']['use_base_' . $name] = 1;
            }

            $product["use_base_" . $name] = $product['store']['use_base_' . $name];

            if ($product['store']['use_base_' . $name]) {
                $product[$name] = $product['base'][$name];
                $product["use_config_" . $name] = $product['base']['use_config_' . $name];
            }
        }

        return $product;
    }

    public function getEstimatedDeliveryDate($order) {

        $storeId = Mage::app()->getRequest()->getParam('store');
        $message = Mage::getStoreConfig("estimateddeliverydate/display_settings/message_in_cart", $storeId);
        $dateFormat = Mage::getStoreConfig("estimateddeliverydate/display_settings/date_format", $storeId);
        $items = $order->getAllItems();
        $method = Mage::getStoreConfig("estimateddeliverydate/custom_options/calculation", $storeId);


        if (count($items)) {
            foreach ($items as $item) {

                $options = $item->getProductOptions();
                $from = 0;
                $to = 0;
                if (isset($options["options"])) {
                    $leadtimes = array();
                    foreach ($options["options"] as $option) {
                        foreach (explode(",", $option['option_value']) as $value_id)
                            $leadtimes[] = explode(",", Mage::getModel('catalog/product_option_value')->load($value_id)->getData("leadtime"));
                    }

                    foreach ($leadtimes as $ld) {
                        if ($method) {
                            $from+=$ld[0];
                            if (isset($ld[1]))
                                $to+=$ld[1];
                        } else {
                            if ($ld[0] > $from) {
                                $from = $ld[0];
                            }
                            if (isset($ld[1]))
                                if ($ld[1] > $to) {
                                    $to = $ld[1];
                                }
                        }
                    }
                }

                $_product = Mage::getModel('catalog/product')->load($item->getProductId());
                $inventory = Mage::getModel('cataloginventory/stock_item')->loadByProduct($_product);
                $type = ((int) $inventory->getQty() <= 0 ) ? "backorders" : "orders";
                $leadtime[] = $this->getLeadtime($_product, $storeId, $type, $from, $to);
            }


            usort($leadtime, array("Wyomind_Estimateddeliverydate_Helper_Data", "cmp_from"));
            $from = $leadtime;
            $final_leadtime = array_pop($from);

            usort($leadtime, array("Wyomind_Estimateddeliverydate_Helper_Data", "cmp_to"));
            $to = array_pop($leadtime);

            $final_leadtime['to'] = $to['to'];

            if ($_product->getBackToStock() && $type == "backorders") {

                $currentGmtTime = Mage::getSingleton('core/date')->gmtTimestamp(strtotime($_product->getBackToStock()));
            } else {
                $currentGmtTime = Mage::getSingleton('core/date')->gmtTimestamp();
            }



            if ($_product->getBackToStock() && $type = "backorders") {

                $currentGmtTime = Mage::getSingleton('core/date')->gmtTimestamp(strtotime($_product->getBackToStock()));
            } else {
                $currentGmtTime = Mage::getSingleton('core/date')->gmtTimestamp();
            }
            $date['from'] = Mage::getSingleton('core/date')->date($dateFormat, $currentGmtTime + 86400 * ($final_leadtime['from']));
            $date['from'] = $this->dateTranslate($date['from']);
            $date['to'] = Mage::getSingleton('core/date')->date($dateFormat, $currentGmtTime + 86400 * ($final_leadtime['to']));
            $date['to'] = $this->dateTranslate($date['to']);
            $week['from'] = floor($final_leadtime['from'] / 7);
            $week['to'] = ceil($final_leadtime['to'] / 7);

            $msg = str_replace(array("{{leadtime_from}}", "{{leadtime_to}}", "{{date_from}}", "{{date_to}}", "{{week_from}}", "{{week_to}}"), array($final_leadtime['from'] + $final_leadtime['back_in_stock'], $final_leadtime['to'] + $final_leadtime['back_in_stock'], $date['from'], $date['to'], $week['from'], $week['to']), $message);

            return json_encode(array('time' => (Mage::getSingleton('core/date')->gmtTimestamp() + 86400 * $final_leadtime['from']), 'msg' => $msg));
        }
    }

}
