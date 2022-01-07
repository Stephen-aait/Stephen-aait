<?php

class Emipro_Customoptions_Block_Adminhtml_Customoptions_Edit_Tab_Options extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Options
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('customoptions/options.phtml');
        return $this;
    }
    protected function _prepareLayout()
    {
		parent::_prepareLayout();
        $this->setChild('add_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label' => Mage::helper('catalog')->__('Add New Option'),
                    'class' => 'add',
                    'id'    => 'add_new_defined_option'
                ))
        );

        $this->setChild('options_box',
            $this->getLayout()->createBlock('emipro_customoptions/adminhtml_customoptions_edit_tab_options_option')
        );

        return $this;
    }

    public function getAddButtonHtml()
    {
     //   return $this->getChildHtml('add_button');
    }

    public function getOptionsBoxHtml()
    {
        return $this->getChildHtml('options_box');
    }
}
