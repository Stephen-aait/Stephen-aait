<?php
/**
 * @company   CueBlocks - http://www.cueblocks.com/
 * @author    Ravinder <ravinder.singh@cueblocks.com>
 */
class CueBlocks_ManualDiscount_Model_Observer
{

    const DISCOUNT_TYPE_FLAT = "flat";

    const DISCOUNT_TYPE_PERCENT = "percent";

    protected $_quoteSession;

    /**
     * Process post data and set usage of Manual Discount into order creation model
     *
     * @param Varien_Event_Observer $observer
     */
    public function processOrderCreationData(Varien_Event_Observer $observer)
    {
        $model = $observer->getEvent()->getOrderCreateModel();
        $request = $observer->getEvent()->getRequest();
        $quote = $model->getQuote();
        $manualDiscount = -1;
        $manualDiscountType = self::DISCOUNT_TYPE_FLAT;
        $auto = false;
        if (isset($request['customdiscount']) || $quote->getManualDiscount() > 0) {
            $manualDiscount = isset($request['customdiscount'])?$request['customdiscount']:$quote->getManualDiscount();
            $manualDiscountType = isset($request['customdiscount_type'])?$request['customdiscount_type']:$quote->getManualDiscountType();
        }

        if ($manualDiscount != -1) {
            if (!isset($request['customdiscount'])) {
                //add blank notice and hide it via css as this is only easy way to remove old messages
                $this->getQuoteSession()->addNotice('<style>li.notice-msg{display:none}</style>');
                $auto = true;
            }

            $this->applyManualDiscount($quote, $manualDiscount, $manualDiscountType, $auto);
        }

        return $this;
    }

    /*
     * Apply Manual Discount
     * */
    public function applyManualDiscount($quote, $discountAmount, $type, $auto=false) 
    {

        $coreHelper = Mage::helper('core');
        if (isset($discountAmount) && is_numeric($discountAmount) && isset($type)) {
            $couponCode = $quote->getCouponCode();
            if (!empty($couponCode)) {
                //unset custom discount from request so that it can't be used during sales rule process
                Mage::app()->getRequest()->setPost('customdiscount', null);
                if (!$auto) {
                    $this->getQuoteSession()->addError(
                        "Sorry you can't apply Manual discount when coupon code is applied please remove it and retry again."
                    );
                }

                return;
            }

            $originalDiscount = number_format($discountAmount, 2);
            try {
                if ($discountAmount >= 0) {
                    if ($type==self::DISCOUNT_TYPE_PERCENT) {
                        if ($discountAmount > 100) {
                            $this->getQuoteSession()->addError(
                                "Sorry you can't enter value more than 100 when applying percentage discount."
                            );
                            return;
                        }

                        //get exact discount from percentage entered
                        $discountAmount = ($quote->getSubtotal()*$discountAmount)/100;
                    } elseif ($discountAmount > $quote->getSubtotal() && !$auto) {
                        $this->getQuoteSession()->addError(
                            "Sorry you can't enter value more than Subtotal when applying flat discount."
                        );
                        return;
                    }

                    //set custom discount in request so that it can be processed during salesrule processing
                    Mage::app()->getRequest()->setPost('customdiscount', $discountAmount);

                    //reinitalise quote totals
                    $quote->setSubtotal(0);
                    $quote->setBaseSubtotal(0);

                    $quote->setSubtotalWithDiscount(0);
                    $quote->setBaseSubtotalWithDiscount(0);

                    $quote->setGrandTotal(0);
                    $quote->setBaseGrandTotal(0);


                    $canAddItems = $quote->isVirtual()? ('billing') : ('shipping');

                    $priceSuffix = Mage::getStoreConfig('tax/calculation/discount_tax')?'_incl_tax':'';
                    //Process manual discount in order address
                    foreach ($quote->getAllAddresses() as $address) {
                        $address->setSubtotal(0);
                        $address->setBaseSubtotal(0);

                        $address->setGrandTotal(0);
                        $address->setBaseGrandTotal(0);

                        $address->collectTotals();

                        /*
                         * Recalculate discount percentage as previous one can't be trusted
                         * in case custom price is used for items
                        */
                        if ($address->getAddressType() == "shipping" && $type==self::DISCOUNT_TYPE_PERCENT) {
                            $discountAmount = ($address->getData('subtotal'.$priceSuffix)*$originalDiscount)/100;
                        }

                        $quote->setSubtotal((float) $quote->getSubtotal() + $address->getSubtotal());
                        $quote->setBaseSubtotal((float) $quote->getBaseSubtotal() + $address->getBaseSubtotal());

                        $quote->setSubtotalWithDiscount(
                            (float) $quote->getSubtotalWithDiscount() + $address->getSubtotalWithDiscount()
                        );
                        $quote->setBaseSubtotalWithDiscount(
                            (float) $quote->getBaseSubtotalWithDiscount() + $address->getBaseSubtotalWithDiscount()
                        );

                        $quote->setGrandTotal((float) $quote->getGrandTotal() + $address->getGrandTotal());
                        $quote->setBaseGrandTotal((float) $quote->getBaseGrandTotal() + $address->getBaseGrandTotal());

                        $quote ->save();

                        $quote->setGrandTotal($quote->getBaseSubtotal()-$discountAmount)
                            ->setBaseGrandTotal($quote->getBaseSubtotal()-$discountAmount)
                            ->setSubtotalWithDiscount($quote->getBaseSubtotal()-$discountAmount)
                            ->setBaseSubtotalWithDiscount($quote->getBaseSubtotal()-$discountAmount)
                            ->save();


                        if ($address->getAddressType()==$canAddItems) {
                            //echo $address->setDiscountAmount; exit;
                            $address->setSubtotalWithDiscount((float) $address->getSubtotalWithDiscount()-$discountAmount);
                            $address->setGrandTotal((float) $address->getGrandTotal()-$discountAmount);
                            $address->setBaseSubtotalWithDiscount((float) $address->getBaseSubtotalWithDiscount()-$discountAmount);
                            $address->setBaseGrandTotal((float) $address->getBaseGrandTotal()-$discountAmount);
                            if ($address->getDiscountDescription()){
                                $address->setDiscountAmount(-($address->getDiscountAmount()-$discountAmount));
                                $address->setDiscountDescription('Manual Discount');
                                $address->setBaseDiscountAmount(-($address->getBaseDiscountAmount()-$discountAmount));
                            } else {
                                $address->setDiscountAmount(-($discountAmount));
                                $address->setDiscountDescription('Manual Discount');
                                $address->setBaseDiscountAmount(-($discountAmount));
                            }

                            $desc['custom_discount'] = 'Manual Discount';
                            $address->setDiscountDescriptionArray($desc);
                            $address->save();
                        }//end: if
                    } //end: foreach

                    //traverse order items and calculate total amount on which discount can be applied
                    $availableAmount = 0;
                    $availableItems = array();
                    foreach ($quote->getAllItems() as $item) {
                        if (!$item->getNoDiscount() && !$this->isParentNoDiscount($item)) {
                            if (!$this->isBundle($item)) {
                                $availableAmount= $availableAmount + $item->getData('row_total'.$priceSuffix);
                            }

                            $availableItems[] = $item;
                        }
                    }

                    if ($type==self::DISCOUNT_TYPE_PERCENT) {
                        $discountAmount = ($availableAmount*$originalDiscount)/100;
                    }

                    //If discount amount is higher then only 100% discount can be applied
                    if ($discountAmount > $availableAmount) {
                        $discountAmount = $availableAmount;
                    }

                    if ($availableAmount > 0) {
                        foreach ($availableItems as $item){
                            //We apply discount amount based on the ratio between the GrandTotal and the RowTotal
                            $rat=$item->getData('price'.$priceSuffix)/$availableAmount;
                            $ratdisc=$discountAmount*$rat;
                            $item->setDiscountAmount(0);
                            $item->setBaseDiscountAmount(0);
                            $item->setDiscountPercent(0);
                            $item->setDiscountAmount(($item->getDiscountAmount()+$ratdisc) * $this->getItemQty($item));
                            $item->setBaseDiscountAmount(($item->getBaseDiscountAmount()+$ratdisc) * $this->getItemQty($item))->save();
                        }

                        $quote->setManualDiscount($originalDiscount);
                        $quote->setManualDiscountType($type);
                        $quote->save();
                        //exit;
                        if (!$auto) {
                            $formattedDiscountAmount = $coreHelper->currency($discountAmount, true, false);
                            if ($type === self::DISCOUNT_TYPE_PERCENT) {
                                $message = "Discount $formattedDiscountAmount out of requested $originalDiscount % has been applied successfully.";
                            } else {
                                $formattedOriginalDiscount = $coreHelper->currency($originalDiscount, true, false);
                                $message = "Discount $formattedDiscountAmount out of requested amount $formattedDiscountAmount has been applied successfully.";
                            }

                            $this->getQuoteSession()->addSuccess($message);
                            return;
                        }
                    } else {
                        if (!$auto) {
                            $this->getQuoteSession()->addError(
                                "Sorry, discount not applied as no item in cart can support discount."
                            );
                        }

                        return;
                    }
                } else {
                    $this->getQuoteSession()->addError(
                        "Sorry, you can't enter value negative value for discount."
                    );
                    return;
                }
            } catch (Mage_Core_Exception $e) {
                $this->getQuoteSession()->addError(
                    $e->getMessage()
                );
            } catch (Exception $e) {
                $this->getQuoteSession()->addException(
                    $e,
                    $this->__('Cannot apply Manual Discount')
                );
            }
        } else {
            $this->getQuoteSession()->addError(
                "Invalid discount amount entered, please check and retry again."
            );
            return;
        }

    }

    /*
     * Get quote session
     * */
    public function getQuoteSession() 
    {
        if (is_null($this->_quoteSession)) {
            $this->_quoteSession = Mage::getSingleton('adminhtml/session_quote');
        }

        return $this->_quoteSession;
    }

    public function getItemQty($item)
    {
        if ($this->isBundleChild($item)) {
            return $item->getParentItem()->getQty();
        }

        return $item->getQty();
    }

    public function isBundle($item)
    {
        if ($item->getProductType() == "bundle") {
            return true;
        }

        return false;
    }

    public function isBundleChild($item)
    {
        if ($item->getParentItem() && $item->getParentItem()->getProductType() == "bundle") {
            return true;
        }

        return false;
    }

    public function isParentNoDiscount($item)
    {
        if ($item->getParentItem() && $item->getParentItem()->getNoDiscount()) {
            return true;
        }

        return false;
    }
}
