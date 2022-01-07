<?php

class Modulesgarden_Teamandtaskorganizer_Model_Observer {

    protected static $_eventsCalled = array();

    /**
     * Magento Event: sales_order_place_after
     * @param Varien_Event_Observer $observer
     */
    public function sales_order_place_after(Varien_Event_Observer $observer) {

        if ($this->_isEventCalledAlready('sales_order_place_after') || !$observer->getEvent()->getOrder()) {
            return;
        } else {
            $eventOrder = $observer->getEvent()->getOrder();
        }

        //Do not create task if order is recurring
        if (!$this->_hasRecurringProduct($eventOrder)) {
            return $this->after_order($observer);
        }
    }

    /**
     * Magento Event: sales_order_invoice_save_after
     * @param Varien_Event_Observer $observer
     */
    public function after_invoice(Varien_Event_Observer $observer) {
        if ($this->_isEventCalledAlready('after_invoice')) {
            return;
        }

        $invoice = $observer->getInvoice();
        if (!$invoice->getOrigData()) {
            $autoTasks = $this->_getAutoTasksCollection('after_invoice');
            foreach ($autoTasks as $autoTask) {
                // @todo conditions such as payment method, subtotal etc
                $autoTask->createTask($invoice);
            }
        }
    }

    public function customer_save_after(Varien_Event_Observer $observer) {
        if ($this->_isEventCalledAlready('')) {
            return;
        }

        $customer = $observer->getCustomer();
        if (!$customer->getOrigData()) { // isObjectNew does not work for customers
            $autoTasks = $this->_getAutoTasksCollection('after_customer');
            foreach ($autoTasks as $autoTask) {
                // @todo conditions such as country etc
                $autoTask->createTask($customer);
            }
        }
    }

    /**
     * Magento Event sales_order_shipment_save_after
     * @param Varien_Event_Observer $observer
     */
    public function after_ship(Varien_Event_Observer $observer) {
        if ($this->_isEventCalledAlready('after_ship')) {
            return;
        }

        $shipment = $observer->getShipment();
        if (!$shipment->getOrigData()) {
            $autoTasks = $this->_getAutoTasksCollection('after_ship');
            foreach ($autoTasks as $autoTask) {
                // @todo conditions such as country etc
                $autoTask->createTask($shipment);
            }
        }
    }

    /**
     * Magento Event review_save_after
     * @param Varien_Event_Observer $observer
     */
    public function after_review(Varien_Event_Observer $observer) {
        if ($this->_isEventCalledAlready('after_review')) {
            return;
        }

        $review = $observer->getObject();
        if (!$review->getOrigData()) {
            $autoTasks = $this->_getAutoTasksCollection('after_review');
            foreach ($autoTasks as $autoTask) {
                // @todo conditions such as product attribute set, prod category etc
                $autoTask->createTask($review);
            }
        }
    }

    /**
     * Magento Event catalog_product_save_after
     * @param Varien_Event_Observer $observer
     */
    public function after_new_product(Varien_Event_Observer $observer) {
        if ($this->_isEventCalledAlready('after_new_product')) {
            return;
        }

        $product = $observer->getProduct();
        if (!$product->getOrigData()) {
            $autoTasks = $this->_getAutoTasksCollection('after_new_product');
            foreach ($autoTasks as $autoTask) {
                // @todo conditions such as product attribute set, prod category etc
                $autoTask->createTask($product);
            }
        }
    }

    /**
     * Magento Event core_abstract_save_after
     * @param Varien_Event_Observer $observer
     */
    public function after_recurring(Varien_Event_Observer $observer)
    {
        if ($this->_isEventCalledAlready('after_recurring')) {
            return;
        }

        $profile = $observer->getObject();
        if ($profile instanceof Mage_Payment_Model_Recurring_Profile && !$profile->getOrigData()) {
            $autoTasks = $this->_getAutoTasksCollection('after_recurring');
            foreach ($autoTasks as $autoTask) {
                // @todo conditions such as product attribute set, prod category etc
                $autoTask->createTask($profile);
            }
        }
    }

    /**
     * Magento Event: sales_order_save_after
     * @param Varien_Event_Observer $observer
     */
    public function after_order_status(Varien_Event_Observer $observer)
    {
        if ($this->_isEventCalledAlready('after_order_status') || !$observer->getEvent()->getOrder()->getOrigData()) {
            return;
        }

        $order = $observer->getEvent()->getOrder();

        if (!($order instanceof Mage_Sales_Model_Order)) {
            return;
        }
        
        $autoTasks  = $this->_getAutoTasksCollection('after_changed_order_status');
        
        try {
            foreach ($autoTasks as $autoTask) {
                $orderStatusConditions = $autoTask->getOrderStatusConditions();

                if($orderStatusConditions instanceof Modulesgarden_Teamandtaskorganizer_Model_OrderConditions AND $orderStatusConditions->isMatchingOrder($order)) {
                    $autoTask->createTask($order);
                }
            }
        }
        catch (Exception $e) {
            Mage::log($e->getMessage(), null, 'teamandtaskorganizer.log');
        }
    }

    protected function after_order(Varien_Event_Observer $observer)
    {
        $order  = Mage::getModel('sales/order')->load($observer->getEvent()->getOrder()->getId());
        $store  = Mage::getModel('core/store')->load($order->getStoreId());
        $quote  = Mage::getModel('sales/quote')->setStore($store)->load($order->getQuoteId())->collectTotals();

        $autoTasks = $this->_getAutoTasksCollection('after_order');

        // fix quote items bug ? not every attribute is set
        foreach ($quote->getAllItems() as $item) {
            $fullProd = Mage::getModel('catalog/product')->getCollection()
                    ->addAttributeToSelect('*')
                    ->addAttributeToFilter('entity_id', $item->getProductId())
                    ->getFirstItem();

            if ($fullProd) {
                $item->setProduct($fullProd);
            }
        }

        foreach ($autoTasks as & $autoTask) {
            $conditions = $autoTask->getConditionsSerialized();

            try {
                if (unserialize($conditions)) { // check conditioons
                    $rule = Mage::getModel('salesrule/rule');
                    $rule->setConditionsSerialized($conditions);

                    $address = $quote->isVirtual() ? $quote->getBillingAddress() : $quote->getShippingAddress();

                    if ($rule->validate($address)) {
                        // conditions ok -> create task
                        $autoTask->createTask($order);
                    }
                } else { // no conditions -> create task
                    $autoTask->createTask($order);
                }
            } catch (Exception $e) {
                Mage::log($e->getMessage(), null, 'teamandtaskorganizer.log');
            }
        }
    }
    
    /**
     * Magento Event sales_order_status_after
     * @param Varien_Event_Observer $observer
     */
    public function order_status_changed(Varien_Event_Observer $observer) {
        if ($this->_isEventCalledAlready('after_changed_order_status')) {
            return;
        }
        
        $autoTasks  = $this->_getAutoTasksCollection('after_changed_order_status');
        $order      = Mage::getModel('sales/order')->load($observer->getOrder()->getId());
        
        $oldStatus  = $order->getOrigData('status');
        $newStatus  = $order->getData('status');
        
        echo '<pre>'; var_dump($oldStatus, $newStatus, $autoTasks, $order);

        foreach ($autoTasks as $autoTask) {
            $orderStatusConditions = $autoTask->getOrderStatusConditions();
            
            if($orderStatusConditions AND $orderStatusConditions->isMatchingOrder($order)) {
                $autoTask->createTask($order);
            }
        }
        
        exit;
    }

    protected function _getAutoTasksCollection($event) {
        return Mage::getSingleton('teamandtaskorganizer/task_auto')->getCollection()
                        ->addFieldToFilter('event', $event)
                        ->addFieldToFilter('status', 1);
    }

    protected function _isEventCalledAlready($name) {
        if (!isset(self::$_eventsCalled[$name])) {
            self::$_eventsCalled[$name] = true;
            return false;
        }
        return true;
    }

    protected function _hasRecurringProduct($order)
    {
        $items = $order->getAllItems();
        foreach ($items as $item) {
            if ($item->getProduct()->getIsRecurring()) {
                return true;
            }
        }

        return false;
    }
}
