<?php
/**
 * Form tag class file
 * 
 * @category    Clarion
 * @package     Clarion_Storelocator
 * @author      Clarion Magento Team
 */
class Clarion_Storelocator_Block_Adminhtml_Storelocator_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
 /*
  * Adds form tag
  */
  protected function _prepareForm()
  {
    $form = new Varien_Data_Form(array(
                                  'id' => 'edit_form',
                                  'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
                                  'method' => 'post',
                                  'enctype' => 'multipart/form-data'
                               )
    );
     
    $form->setUseContainer(true);
    // This line is important because it is the line that actually causes the form renderer to output the surrounding <form> tags.
    $this->setForm($form);
    return parent::_prepareForm();
  }
}