<?php

class Wyomind_Estimateddeliverydate_Block_Notification extends Mage_Core_Block_Messages {

    public function __construct() {
      
    }
     function _toHtml(){
          return Mage::helper("estimateddeliverydate")->getCartMessage(Mage::app()->getStore()->getId());
     }
}

