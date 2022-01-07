<?php

class Wyomind_Estimateddeliverydate_Model_CatalogInventory_Stock_Item extends Mage_CatalogInventory_Model_Stock_Item {

    public function checkQuoteItemQty($qty, $summaryQty, $origQty = 0) {
       
       
        
        $result = new Varien_Object();
        $result->setHasError(false);

        if (!is_numeric($qty)) {
            $qty = Mage::app()->getLocale()->getNumber($qty);
        }

        /**
         * Check quantity type
         */
        $result->setItemIsQtyDecimal($this->getIsQtyDecimal());

        if (!$this->getIsQtyDecimal()) {
            $result->setHasQtyOptionUpdate(true);
            $qty = intval($qty);

            /**
             * Adding stock data to quote item
             */
            $result->setItemQty($qty);

            if (!is_numeric($qty)) {
                $qty = Mage::app()->getLocale()->getNumber($qty);
            }
            $origQty = intval($origQty);
            $result->setOrigQty($origQty);
        }

        if ($this->getMinSaleQty() && $qty < $this->getMinSaleQty()) {
            $result->setHasError(true)
                    ->setMessage(
                            Mage::helper('cataloginventory')->__('The minimum quantity allowed for purchase is %s.', $this->getMinSaleQty() * 1)
                    )
                    ->setErrorCode('qty_min')
                    ->setQuoteMessage(Mage::helper('cataloginventory')->__('Some of the products cannot be ordered in requested quantity.'))
                    ->setQuoteMessageIndex('qty');
            return $result;
        }

        if ($this->getMaxSaleQty() && $qty > $this->getMaxSaleQty()) {
            $result->setHasError(true)
                    ->setMessage(
                            Mage::helper('cataloginventory')->__('The maximum quantity allowed for purchase is %s.', $this->getMaxSaleQty() * 1)
                    )
                    ->setErrorCode('qty_max')
                    ->setQuoteMessage(Mage::helper('cataloginventory')->__('Some of the products cannot be ordered in requested quantity.'))
                    ->setQuoteMessageIndex('qty');
            return $result;
        }

        $result->addData($this->checkQtyIncrements($qty)->getData());
        if ($result->getHasError()) {
            return $result;
        }

        if (!$this->getManageStock()) {
            $msg = Mage::helper('estimateddeliverydate')->getProductInCartMessage($this->loadProduct(),0);
            if ($msg != "") {
                $result->setMessage($msg);
            }
            return $result;
        }

        if (!$this->getIsInStock()) {
            $result->setHasError(true)
                    ->setMessage(Mage::helper('cataloginventory')->__('This product is currently out of stock.'))
                    ->setQuoteMessage(Mage::helper('cataloginventory')->__('Some of the products are currently out of stock'))
                    ->setQuoteMessageIndex('stock');
            $result->setItemUseOldQty(true);
            return $result;
        }
        
        if (!$this->checkQty($summaryQty) || !$this->checkQty($qty)) {
            $message = Mage::helper('cataloginventory')->__('The requested quantity for "%s" is not available.', $this->getProductName());
            $result->setHasError(true)
                    ->setMessage($message)
                    ->setQuoteMessage($message)
                    ->setQuoteMessageIndex('qty');
            return $result;
        } else {
            $backorderQty=0;
            if (($this->getQty() - $summaryQty) < 0) {
                if ($this->getProductName()) {

                    if ($this->getIsChildItem()) {
                        $backorderQty = ($this->getQty() > 0) ? ($summaryQty - $this->getQty()) * 1 : $qty * 1;
                        if ($backorderQty > $qty) {
                            $backorderQty = $qty;
                        }

                        $result->setItemBackorders($backorderQty);
                    } else {
                        $orderedItems = $this->getOrderedItems();
                        $itemsLeft = ($this->getQty() > $orderedItems) ? ($this->getQty() - $orderedItems) * 1 : 0;
                        $backorderQty = ($itemsLeft > 0) ? ($qty - $itemsLeft) * 1 : $qty * 1;

                        if ($backorderQty > 0) {
                            $result->setItemBackorders($backorderQty);
                        }
                        $this->setOrderedItems($orderedItems + $qty);
                    }
                    if ($this->getBackorders() == Mage_CatalogInventory_Model_Stock::BACKORDERS_YES_NOTIFY) {

                        if (!$this->getIsChildItem()) {

                            $result->setMessage(
                                    Mage::helper('cataloginventory')->__('This product is not available in the requested quantity. %s of the items will be backordered.', ($backorderQty * 1))
                                    . " " . Mage::helper('estimateddeliverydate')->getProductInCartMessage($this->loadProduct(),$backorderQty)
                            );
                        } else {
                            $result->setMessage(
                                    Mage::helper('cataloginventory')->__('"%s" is not available in the requested quantity. %s of the items will be backordered.', $this->getProductName(), ($backorderQty * 1))
                                    . " " . Mage::helper('estimateddeliverydate')->getProductInCartMessage($this->loadProduct(),$backorderQty)
                            );
                        }
                    } elseif (Mage::app()->getStore()->isAdmin()) {
                        $result->setMessage(
                                Mage::helper('cataloginventory')->__('The requested quantity for "%s" is not available.', $this->getProductName())
                                . " " . Mage::helper('estimateddeliverydate')->getProductInCartMessage($this->loadProduct(),$backorderQty)
                        );
                    } else {
                        $result->setMessage(
                                Mage::helper('estimateddeliverydate')->getProductInCartMessage($this->loadProduct(),$backorderQty)
                        );
                    }
                }
            } else {
                $msg = Mage::helper('estimateddeliverydate')->getProductInCartMessage($this->loadProduct(),$backorderQty);
                if ($msg != "") {
                    $result->setMessage($msg);
                }
                if (!$this->getIsChildItem()) {
                    $this->setOrderedItems($qty + (int) $this->getOrderedItems());
                }
            }
        }

        return $result;
    }

    function loadProduct() {
        return Mage::getModel("catalog/product")->load($this->getProductId());
    }

    function loadProductName() {
        if (!$this->getProductName())
            return $this->loadProductName();
        return $this->getProductName();
    }

}
