<?php
 /**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */

class Amasty_Orderattach_Helper_Attachment extends Mage_Core_Helper_Abstract
{
    /**
     * Return selected store id from request
     *
     * @return integer
     */
    public function getSelectedStoreId()
    {
        return (int)$this->_getRequest()->getParam('store', Mage_Core_Model_App::ADMIN_STORE_ID);
    }
}