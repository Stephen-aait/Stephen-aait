<?php
/**
 * Description tab class file
 * 
 * @category    Clarion
 * @package     Clarion_Storelocator
 * @author      Clarion Magento Team
 */

class Clarion_Storelocator_Block_Adminhtml_Storelocator_Edit_Tab_Description extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Load Wysiwyg on demand and Prepare layout
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }
    
    protected function _prepareForm()
    {
        $model = Mage::registry('storelocator_data');
        
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('form_General', array('legend'=>Mage::helper('clarion_storelocator')->__('Description')));
        
        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(
            array('tab_id' => $this->getTabId())
        );
        
        $fieldset->addField('description', 'editor', array(
            'label'     => Mage::helper('clarion_storelocator')->__('Description'),
            'title' => Mage::helper('clarion_storelocator')->__('Description'),
            'name'      => 'description',
            'config'    => Mage::getSingleton('cms/wysiwyg_config')->getConfig(),
            'style'     => 'height:20em; width:53em',
        ));
          
       $form->setValues($model->getData());
       return parent::_prepareForm();
    }
}