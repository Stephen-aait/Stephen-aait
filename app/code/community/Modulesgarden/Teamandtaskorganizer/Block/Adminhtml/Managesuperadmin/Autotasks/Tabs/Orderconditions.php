<?php

/* * ********************************************************************
 * Customization Services by ModulesGarden.com
 * Copyright (c) ModulesGarden, INBS Group Brand, All Rights Reserved 
 * (2014-05-09, 09:38:03)
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
 * ******************************************************************** */

/**
 * @author Grzegorz Draganik <grzegorz@modulesgarden.com>
 */

class Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_Managesuperadmin_Autotasks_Tabs_Orderconditions extends Mage_Adminhtml_Block_Widget_Form {

	protected function _prepareForm() {
		$model = Mage::registry('current_promo_quote_rule');

		$form = new Varien_Data_Form();

		$form->setHtmlIdPrefix('rule_');

		$renderer = Mage::getBlockSingleton('adminhtml/widget_form_renderer_fieldset')
				->setTemplate('promo/fieldset.phtml')
				->setNewChildUrl($this->getUrl('adminhtml/promo_quote/newConditionHtml/form/rule_conditions_fieldset'));

		$fieldset = $form->addFieldset('conditions_fieldset', array(
					'legend' => Mage::helper('salesrule')->__('Apply the rule only if the following conditions are met (leave blank for all products)')
				))->setRenderer($renderer);

		$fieldset->addField('conditions', 'text', array(
			'name' => 'conditions',
			'label' => Mage::helper('salesrule')->__('Conditions'),
			'title' => Mage::helper('salesrule')->__('Conditions'),
		))->setRule($model)->setRenderer(Mage::getBlockSingleton('rule/conditions'));

		$form->setAction($this->getUrl('*/*/saveConditions'))
			->setMethod('post');
		$form->setValues($model->getData());
		$this->setForm($form);

		return parent::_prepareForm();
	}

}
