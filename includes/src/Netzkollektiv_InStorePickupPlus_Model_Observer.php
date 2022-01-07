<?php
class Netzkollektiv_InStorePickupPlus_Model_Observer {
	protected $_currentHelperName = '';

    public function addTooltip($observer) {
        $event = $observer->getEvent();

        $element = $event->getElement();
        $helper = $event->getHelper();

        $this->_currentHelperName = $helper;
        if (($tooltip = $this->_getExternalContent($element->getName(),$helper,'tooltips')) !== null) {
            $element->tooltip = $tooltip;
        }
    }

    public function addComment($observer) {
        $event = $observer->getEvent();

        $element = $event->getElement();
        $helper = $event->getHelper();

        if (($comment = $this->_getExternalContent($element->getName(),$helper,'comments')) !== null) {
            $element->comment = $comment;
        }
    }

    public function addFieldConfig($observer) {
        $event = $observer->getEvent();

        $element = $event->getElement();
        $field = $event->getField();

        $helper = $this->_currentHelperName;
        if (($fieldConfig = $this->_getExternalContent($element->getName(),$helper,'field-config')) !== null) {
            if (is_array($fieldConfig)) {
                $field->addData($fieldConfig);
            }
        }
    }

    protected function _getExternalContent($name,$helper,$type) {
        if ($helper != $this->_getExtensionName()) {
            return null;
        }

        if (!isset($this->_externalContent)) {
            $this->_externalContent = Mage::helper('core')->jsonDecode(
                @file_get_contents('http://magext.netzkollektiv.com/?'.$helper)
            );
        }

        if ($name != ''
            && isset($this->_externalContent[$type][$name])
        ) {
            return $this->_externalContent[$type][$name];
        }
        return null;
    }
    
    protected function _getExtensionName() {
        $classParts = explode('_',get_class($this));
        if (isset($classParts[1])) {
            return strtolower($classParts[1]);
        }
        return '';
    }
}