<?php

class Wyomind_Estimateddeliverydate_Block_Adminhtml_Manageleadtimes extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_manageleadtimes';
        $this->_blockGroup = 'estimateddeliverydate';
        $this->_headerText = Mage::helper('estimateddeliverydate')->__('Manage Leadtime by attribute');

        $this->_addButton('save', array(
            'label' => Mage::helper('estimateddeliverydate')->__('Save all changes'),
            'class' => 'save',
            'onclick' => "estimateddeliverydate.save('" . $this->getUrl('*/*/save')."')"
        ));

        parent::__construct();
        $this->setTemplate('estimateddeliverydate/manageleadtimes.phtml');
        $this->removeButton('add');
    }

}

