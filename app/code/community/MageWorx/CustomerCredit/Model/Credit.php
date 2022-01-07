<?php

/**
 * MageWorx
 * Loyalty Booster Extension
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @copyright  Copyright (c) 2016 MageWorx (http://www.mageworx.com/)
 */
class MageWorx_CustomerCredit_Model_Credit extends Mage_Core_Model_Abstract
{
    protected $_eventPrefix = 'mageworx_customercredit_credit';
    protected $_eventObject = 'credit';
    protected $creditLog;
    protected $customer;

    public function __construct($customer = null)
    {
        parent::__construct();
        $this->customer = $customer;

        if ($this->customer) {
            $this->setCustomerId($this->customer->getId());
            $this->setWebsiteId($this->customer->getWebsiteId());
            $this->validate();
        }

    }

    protected function _construct()
    {
        $this->_init('mageworx_customercredit/credit');
        $this->creditLog = Mage::getModel('mageworx_customercredit/credit_log')->setCreditModel($this);
    }

    /**
     * Before save
     * @return object
     */
    protected  function _beforeSave()
    {
        $this->validate();
        $this->prepare();
        $this->validateExpiration();
        return parent::_beforeSave();
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    protected function _afterSave()
    {
        $this->creditLog->save();
        return parent::_afterSave();
    }

    /**
     * @return $this
     */
    protected function validateExpiration() {
        $post = Mage::app()->getRequest()->getPost();
        if (isset($post['customercredit'])) {
            $creditPost = $post['customercredit'];
            $this->setEnableExpiration($creditPost['enable_expiration']);
            if (isset($creditPost['expiration_time']) && !empty($creditPost['expiration_time'])) {
                $time1 = strtotime(now());
                $time2 = strtotime($creditPost['expiration_time']);
                if ($time2 - $time1 >= 0) {
                    $this->setExpirationTime($creditPost['expiration_time']);
                } else {
                    $session = Mage::getSingleton('adminhtml/session');
                    $session->addError(Mage::helper('mageworx_customercredit')->__('You cannot save past periods as the expiration date.'));
                }
            }
        }
        return $this;
    }

    /**
     * @return $this|bool
     */
    protected function validate() {

        $helper = Mage::helper('mageworx_customercredit');
        //validate cron
        if ($this->getIsCron()) {
            return $this;
        }

        //validate API
        if ($this->getIsApi()) {
            foreach (Mage::app()->getWebsites() as $website) {
                if ($website->getIsDefault()) {
                    $this->setWebsiteId($website->getId());
                }
            }
        }

        //validate website
        if ($helper->isScopePerWebsite()) {
            if (!$this->getWebsiteId()) {
                if (!Mage::app()->getStore()->isAdmin()) {
                    if (!$this->getWebsiteId()) {
                        $this->setWebsiteId(Mage::app()->getStore()->getWebsiteId());
                    }
                } else {
                    if (!$this->getWebsiteId()) {
                        $websites = Mage::app()->getWebsites();
                        $website = array_shift($websites);
                        $this->setWebsiteId($website->getId());
                    }
                }
            }
            if (!$this->getWebsiteId() && !Mage::getSingleton('admin/session')->getUser()) {
                Mage::throwException($helper->__('Website ID is not set'));
            }
        } else {
            $this->setWebsiteId(0); // global scope
        }

        //load credit data
        $this->getResource()->loadByCustomerAndWebsite($this, $this->getCustomerId(), $this->getWebsiteId());
        return $this;
    }

    /**
     * Validate and prepare data before save
     * @return object|boolean
     */
    protected function prepare()
    {
        $helper = Mage::helper('mageworx_customercredit');

        //validate credit value
        if ($this->hasValueChange()) {
            $value = (float)$this->getValue();
            $add = (float)$this->getValueChange();
            if ($add > 0) {
                $customerGroup = $this->customer->getGroupId();
                $time = $helper->getDefaultExpirationPeriod();
                if (Mage::getStoreConfig('mageworx_customercredit/expiration/default_expiration_period_' . $customerGroup)) {
                    $time = Mage::getStoreConfig('mageworx_customercredit/expiration/default_expiration_period_' . $customerGroup);
                }
                $this->setData('expiration_time', date('Y-m-d', time() + 3600 * 24 * $time));
            }
            if (Mage::registry('customer_credit_order_place_amount_value')) {
                $add = $add * Mage::getStoreConfig('mageworx_customercredit/main/exchange_rate');
            }

            $this->setValueChange($add);
            $this->setValue($value + $add);
        }

        return $this;
    }

    /**
     * Update credit log
     * @param $creditValue
     * @param $comment
     * @return MageWorx_CustomerCredit_Model_Credit
     */
    public function update($creditValue, $comment ='')
    {
        $this->setValueChange($creditValue);
        $this->creditLog
            ->setComment($comment);
        $this->save();
        return $this;
    }

    /**
     * Refill the credit using Recharge Code
     * Method should be called before changing recharge code credit value and saving
     * @var MageWorx_CustomerCredit_Model_Code $code
     * @return MageWorx_CustomerCredit_Model_Credit
     */
    public function processRefill($code)
    {
        $this->creditLog
            ->setRechargeCode($code->getCode());
        return $this->update($code->getCredit());
    }

    /**
     * Use credit to purchase order
     * @param Mage_Sales_Model_Order $order
     * @return MageWorx_CustomerCredit_Model_Credit
     */
    public function useCredit($order)
    {
        Mage::getSingleton('checkout/session')->setInternalCredit();
        $needUseCreditMarker = Mage::registry('need_reduce_customercredit');
        $valueChange = -$order->getBaseCustomerCreditAmount();
        if ($needUseCreditMarker) {
            $valueChange = -$needUseCreditMarker;
        }
        $this->creditLog
            ->setActionType(MageWorx_CustomerCredit_Model_Credit_Log::ACTION_TYPE_USED)
            ->setOrder($order);
        return $this->update($valueChange);
    }

    /**
     * Return ordered amount to customer's credit after refund
     * @param Mage_Sales_Model_Order_Creditmemo $creditmemo
     * @return MageWorx_CustomerCredit_Model_Credit
     */
    public function processRefund($creditmemo, $post)
    {
        $helper = Mage::helper('mageworx_customercredit');
        // cancel credit rule
        $order = $creditmemo->getOrder();
        if (!$order || !$order->getId()) return $this;
        $method = $order->getPayment()->getMethod();
        if ($method == 'ccsave') return true;
        $orderCreditRules = Mage::getResourceModel('mageworx_customercredit/credit_log_collection')->addOrderFilter($order->getId())->addActionTypeFilter(3);
        if ($orderCreditRules) {
            $minusCredit = 0;
            foreach ($orderCreditRules as $rule) {
                $rulesCustomer = Mage::getModel('mageworx_customercredit/rules_customer')->load($rule->getRulesCustomerId());
                if ($rulesCustomer) {
                    $rulesCustomer->delete();
                    $minusCredit += $rule->getValueChange();
                }
            }
            if ($minusCredit > 0) {
                $this->creditLog
                    ->setActionType(MageWorx_CustomerCredit_Model_Credit_Log::ACTION_TYPE_REFUNDED)
                    ->setCreditmemo($creditmemo)
                    ->setCreditRule(1)
                    ->setOrder($order);
                $this->update(-$minusCredit);
            }
        }

        // refund and return credit
        if (isset($post['credit_return'])) {
            $baseCreditAmountReturn = floatval($post['credit_return']);
            // validation                
            $total = $order->getBaseGrandTotal() + ($helper->getValueExchangeRateMultiplied($order->getBaseCustomerCreditAmount()));
            if ($baseCreditAmountReturn > $total) $baseCreditAmountReturn = $total;
        } else {
            $baseCreditAmountReturn = $helper->getValueExchangeRateMultiplied($creditmemo->getBaseCustomerCreditAmount());
        }

        if ($baseCreditAmountReturn > 0) {
            $this->creditLog
                ->setActionType(MageWorx_CustomerCredit_Model_Credit_Log::ACTION_TYPE_REFUNDED)
                ->setCreditmemo($creditmemo)
                ->setOrder($creditmemo->getOrder());
            $this->update($baseCreditAmountReturn);
        }
        return $this;
    }

    /**
     * Cancel order
     * @param Mage_Sales_Model_Order $order
     * @return MageWorx_CustomerCredit_Model_Credit
     */
    public function processCancel($order)
    {
        $helper = Mage::helper('mageworx_customercredit');
        // cancel and return credit
        if ($order->getBaseCustomerCreditAmount() > 0) {
            $this->creditLog
                ->setActionType(MageWorx_CustomerCredit_Model_Credit_Log::ACTION_TYPE_CANCELED)
                ->setOrder($order);
            if (Mage::registry('customercredit_order_real_edit')) {
                $this->creditLog->setActionType(MageWorx_CustomerCredit_Model_Credit_Log::ACTION_TYPE_ORDER_CANCEL_AFTER_EDIT);
            }
            $this->update($helper->getValueExchangeRateMultiplied($order->getBaseCustomerCreditAmount()));
        }
        return $this;
    }

    /**
     * @param $syncType
     * @param $item
     * @return $this
     */
    public function processSync($syncType, $item) {
        $valueChange = 0;
        if ($syncType == MageWorx_CustomerCredit_Model_System_Config_Source_Sync::ACTION_TYPE_APPEND) {
            $valueChange = $item->getPointsBalance();
        } elseif ($syncType == MageWorx_CustomerCredit_Model_System_Config_Source_Sync::ACTION_TYPE_REPLACE) {
            $valueChange = 0 - floatval($this->getValue()) + floatval($item->getPointsBalance());
        }
        $this->creditLog->setActionType(MageWorx_CustomerCredit_Model_Credit_Log::ACTION_TYPE_SYNC);
        return $this->update($valueChange);
    }

    /**
     * @param $creditValue
     * @param $comment
     * @return $this
     */
    public function processImport($creditValue, $comment) {
        $this->creditLog->setActionType(MageWorx_CustomerCredit_Model_Credit_Log::ACTION_TYPE_IMPORT);
        return $this->update($creditValue, $comment);
    }

    /**
     * @return $this
     */
    public function processExpire() {
        $this->creditLog->setActionType(MageWorx_CustomerCredit_Model_Credit_Log::ACTION_TYPE_EXPIRED);
        return $this->update(0-$this->getValue());
    }

    /**
     * @param $credit
     * @return $this
     */
    public function processCreateCode($credit) {
        $this->creditLog->setActionType(MageWorx_CustomerCredit_Model_Credit_Log::ACTION_TYPE_CODE_CREATED);
        return $this->update($credit);
    }

    /**
     * @return $this
     */
    public function processSubscription() {
        $creditAmount = Mage::helper('mageworx_customercredit')->getNewsletterSubscription();
        if (!$creditAmount) {
            return $this;
        }

        $logCollection = $this->creditLog->getCollection();
        $logCollection->addCustomerFilter($this->customer->getId())
            ->addActionTypeFilter(MageWorx_CustomerCredit_Model_Credit_Log::ACTION_TYPE_NEWSLETTER_SUBSCRIPTION);
        if ($logCollection->getSize()) {
            if ($lastItem = $logCollection->getLastItem()) {
                return $this;
            }
        }

        $this->creditLog->setActionType(MageWorx_CustomerCredit_Model_Credit_Log::ACTION_TYPE_NEWSLETTER_SUBSCRIPTION);

        return $this->update($creditAmount);
    }

    /**
     * @param Mage_Tag_Model_Tag $object
     * @return $this
     */
    public function processProductTag($object) {
        $creditAmount = Mage::helper('mageworx_customercredit')->getProductTag();
        if (!$creditAmount) {
            return $this;
        }

        if (!$object->getData('first_customer_id') || ($object->getStatus() != 1)){
            return $this;
        }

        $this->creditLog
            ->setActionType(MageWorx_CustomerCredit_Model_Credit_Log::ACTION_TYPE_PRODUCT_TAG)
            ->setTag($object);

        return $this->update($creditAmount);
    }

    /**
     * @param Mage_Review_Model_Review $object
     * @return $this
     */
    public function processProductReview($object) {
        $creditAmount = Mage::helper('mageworx_customercredit')->getProductReview();
        if (!$creditAmount) {
            return $this;
        }

        if(!$object->getCustomerId() || ($object->getStatusId() != 1)) {
            return $this;
        }

        $this->creditLog
            ->setActionType(MageWorx_CustomerCredit_Model_Credit_Log::ACTION_TYPE_PRODUCT_REVIEW)
            ->setReview($object);

        return $this->update($creditAmount);
    }

    /**
     * @return $this
     */
    public function processBirthday() {
        $creditAmount = Mage::helper('mageworx_customercredit')->getCustomerBirthday();
        if (!$creditAmount) {
            return $this;
        }

        $logCollection = $this->creditLog->getCollection();
        $logCollection->addCustomerFilter($this->customer->getId())
            ->addActionTypeFilter(MageWorx_CustomerCredit_Model_Credit_Log::ACTION_TYPE_CUSTOMER_BIRTHDAY);
        if ($logCollection->getSize()) {
            $lastItem = $logCollection->getLastItem();
            if (time() - strtotime($lastItem->getActionDate()) < 31104000) {
                return $this;
            }
        }
        $this->creditLog->setActionType(MageWorx_CustomerCredit_Model_Credit_Log::ACTION_TYPE_CUSTOMER_BIRTHDAY);
        return $this->update($creditAmount);
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @return $this
     */
    public function processCreditProductPurchase($order) {
        $creditQty = $this->getCreditProductQtyPurchased($order);
        if (!$creditQty) {
            return $this;
        }

        $this->creditLog
            ->setOrder($order)
            ->setActionType(MageWorx_CustomerCredit_Model_Credit_Log::ACTION_TYPE_CREDIT_PRODUCT);
        return $this->update($creditQty);
    }

    /**
     * Get quantity of Credit Product items purchased 
     * @param Mage_Sales_Model_Order $order
     * @return float
     */
    protected function getCreditProductQtyPurchased($order)
    {
        $creditProductSku = Mage::helper('mageworx_customercredit')->getCreditProductSku();

        if (!$creditProductSku) {
            return 0;
        }

        $creditLog = $this->creditLog->loadByOrderAndAction($order->getId(), MageWorx_CustomerCredit_Model_Credit_Log::ACTION_TYPE_CREDIT_PRODUCT);
        if ($creditLog && $creditLog->getId()) {
            return 0;
        }

        $qty = 0;
        $allItems = $order->getAllItems();

        foreach ($allItems as $item) {
            if ($item->getSku() == $creditProductSku) {
                $qty += intval($item->getQtyInvoiced());
            }
        }

        return $qty;
    }

    /**
     * @param float $credit
     * @return $this
     */
    public function processDecreaseCredit($credit) {
        $this->creditLog->setActionType(MageWorx_CustomerCredit_Model_Credit_Log::ACTION_TYPE_CODE_CREATED);
        return $this->update(0 - abs($credit));
    }

    /**
     * @param $creditChange
     * @param $order
     * @param $rule
     * @param $rulesCustomerId
     * @param $action
     * @return $this
     */
    public function processRule($creditChange, $order, $rule, $rulesCustomerId, $action) {
        $this->creditLog
            ->setOrder($order)
            ->setRuleId($rule['rule_id'])
            ->setRuleName($rule['name'])
            ->setRulesCustomerId($rulesCustomerId)
            ->setActionType($action);
        return $this->update($creditChange);
    }

    public function getCustomer() {
        return $this->customer;
    }
}