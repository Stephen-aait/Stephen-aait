<?php

/**********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-10-31, 12:07:11)
 * 
 *
 *  CREATED BY MODULESGARDEN       ->        http://modulesgarden.com
 *  CONTACT                        ->       contact@modulesgarden.com
 *
 *
 *
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 *
 *
 **********************************************************************/

/**
 * @author Grzegorz Draganik <grzegorz@modulesgarden.com>
 */

class Modulesgarden_Base_Block_Adminhtml_System_Config_Form_Fieldset_Installedratio extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface {

	protected $_template = 'modulesgardenbase/system/config/form/fieldset/installedratio.phtml';
        
        protected $_calculated;
        
        public function _construct() {
            parent::_construct();
            
            $this->_calculated = Mage::getModel('modulesgardenbase/mage')->calculateCustomization();
        }
        
	public function render(Varien_Data_Form_Element_Abstract $element) {
		return $this->toHtml();
	}
	
	public function getRatio() {
		return $this->_calculated['ratio'];
	}
        
        public function getRatioLabel() {
            $labels = array_reverse(Modulesgarden_Base_Model_Mage::$ratioLabels, true);
            $ratio  = $this->getRatio();
            
            foreach($labels as $minRatio => $label) {
                if($ratio >= $minRatio) {
                    return Mage::helper('modulesgardenbase')->__($label);
                }
            }
        }
	
	public function getDebugString() {
		return print_r($this->_calculated, true);
	}

}
