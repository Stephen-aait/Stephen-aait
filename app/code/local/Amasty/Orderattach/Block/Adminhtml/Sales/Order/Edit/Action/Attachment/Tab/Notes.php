<?php
 /**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Orderattach
 */

class Amasty_Orderattach_Block_Adminhtml_Sales_Order_Edit_Action_Attachment_Tab_Notes
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected function _construct()
    {
        parent::_construct();
    }

    protected function _prepareForm()
    {

        $orderIds = Mage::registry('order_ids');
        $form = new Varien_Data_Form();
        $fieldset = $form->addFieldset('fields', array('legend'=>Mage::helper('amorderattach')->__('Order Notes'), 'class' => 'fieldset'));

        $fieldset->addField('order_ids', 'hidden', array(
            'name' => 'order_ids',
            'value' => implode(',', $orderIds),
        ));

        $attachments = Mage::getModel('amorderattach/field')->getCollection()->addFieldToFilter('is_enabled', 1)
            ->addFieldToFilter('type', array('nin' => array('file', 'file_multiple')));
        foreach ($attachments as $attachment) {
            /**
             * @var Amasty_Orderattach_Model_Field $attachment
             */
            $fieldTypes = array(
                'string'        => 'text',
                'link'          => 'text',
                'text'          => 'textarea',
            );
            $fieldType = array_key_exists($attachment->getType(), $fieldTypes)
                ? $fieldTypes[$attachment->getType()] : $attachment->getType();
            $selectOptions = explode(',',trim($attachment->getOptions(), ','));
            $selectOptions = array_combine($selectOptions, $selectOptions);

            $fieldOptions = array(
                'name' => $attachment->getCode(),
                'label' => Mage::helper('amorderattach')->__($attachment->getLabel()),
                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
                'values' => $selectOptions);

            $element = $fieldset->addField($attachment->getCode(), $fieldType, $fieldOptions);
            $element->setAfterElementHtml($this->_getAdditionalElementHtml($element));

        }

        $this->setForm($form);
    }

    /**
     * Retrive attributes for product massupdate
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->helper('adminhtml/catalog_product_edit_action_attribute')->getAttributes()->getItems();
    }

    /**
     * Custom additional elemnt html
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getAdditionalElementHtml($element)
    {
        // Add name attribute to checkboxes that correspond to multiselect elements
        $nameAttributeHtml = ($element->getExtType() === 'multiple') ? 'name="' . $element->getId() . '_checkbox"'
            : '';
        return '<span class="attribute-change-checkbox"><input type="checkbox" id="' . $element->getId()
        . '-checkbox" ' . $nameAttributeHtml . ' onclick="toogleFieldEditMode(this, \'' . $element->getId()
        . '\')" /><label for="' . $element->getId() . '-checkbox">' . Mage::helper('catalog')->__('Change')
        . '</label></span>
                <script type="text/javascript">initDisableFields(\''.$element->getId().'\')</script>';
    }

    /**
     * ######################## TAB settings #################################
     */
    public function getTabLabel()
    {
        return Mage::helper('amorderattach')->__('Order Notes');
    }

    public function getTabTitle()
    {
        return Mage::helper('amorderattach')->__('Order Notes');
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }
}
