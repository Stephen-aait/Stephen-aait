<?php

/**
 * @category    Genmato
 * @package     Genmato_Core
 * @copyright   Copyright (c) 2013 Genmato BV (http://www.genmato.net)
 */

class Genmato_Core_Block_System_Config_Form_Fieldset_Extensions extends Mage_Adminhtml_Block_System_Config_Form_Fieldset {

	protected $_dummyElement;
	protected $_fieldRenderer;
	protected $_values;

	public function render(Varien_Data_Form_Element_Abstract $element) {
		$html    = $this->_getHeaderHtml($element);

        $extension = str_replace(
            '_Helper_Data',
            '',
            Mage::getConfig()->getHelperClassName(str_replace('genmato_core_', '', $element->getId())))
        ;

        $modules = array_keys((array)Mage::getConfig()->getNode('modules')->children());
		sort($modules);

		foreach ($modules as $moduleName) {

            if ($moduleName == $extension) {
                $html .= $this->_getExtensionHtml($element, $moduleName, $element->getId());
            }
		}
		$html .= $this->_getFooterHtml($element);

		return $html;
	}

    protected function _getExtensionHtml($element, $moduleName, $elementId)
    {
        $moduleShortName = str_replace('Genmato_', '', $moduleName);

        $ver = (Mage::getConfig()->getModuleConfig($moduleName)->version);
        $data = Mage::helper('genmato_core')->getLatestExtensionData($moduleName);
        if (!$data) {
            $data = array('version' => 'unavailable', 'released' => 'unknown');
        }

        $html = $this->_getFieldHtml($element, $elementId . 'module_name', 'Module name', $moduleShortName);
        $html .= $this->_getFieldHtml($element, $elementId . 'module_version', 'Installed version', $ver);
        if ($data['released'] != 'unknown') {
            $date = ' (' . date("Y-m-d", strtotime($data['released'])) . ')';
            $html .= $this->_getFieldHtml(
                $element,
                $elementId . 'module_latest',
                'Latest version',
                $data['version'] . $date
            );
        }

        return $html;
    }

    protected function _getFieldRenderer() {
		if (empty($this->_fieldRenderer)) {
			$this->_fieldRenderer = Mage::getBlockSingleton('adminhtml/system_config_form_field');
		}

		return $this->_fieldRenderer;
	}

    protected function _getFieldHtml($fieldset, $id, $name, $value)
    {

        $field = $fieldset
            ->addField(
                $id,
                'label',
                array(
                    'name' => $id,
                    'label' => $name,
                    'value' => $value,
                )
            )
            ->setRenderer($this->_getFieldRenderer());

        return $field->toHtml();
    }

}
