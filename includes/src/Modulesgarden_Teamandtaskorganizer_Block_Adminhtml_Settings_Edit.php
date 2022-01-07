<?php

/**
 * @author Rafał Samojedny <rafal@modulesgarden.com>
 */
class Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_Settings_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

	public function __construct() {
		parent::__construct();

		$this->_objectId = 'id';
		$this->_blockGroup = 'teamandtaskorganizer';
		$this->_controller = 'adminhtml_settings';

        if($this->getRequest()->isAjax()) {
            $this->_updateButton('save', 'class', 'settings-ajax-save');
            $this->_updateButton('save', 'onclick', '');
            $this->_removeButton('saveandcontinue');
        }
		$this->_removeButton('back');
		$this->_removeButton('delete');
		$this->_removeButton('reset');
		
		// modulesgarden template
		$this->setTemplate('modulesgardenbase/widget/form/container.phtml');
		$this->setSkipHeaderCopy(true);
	}

	public function getHeaderText() {
		return Mage::helper('teamandtaskorganizer')->__('Edit Settings');
	}
	
	protected function _toHtml() {
		return '<div class="main-col-inner">' . parent::_toHtml() . '</div>';
	}

}