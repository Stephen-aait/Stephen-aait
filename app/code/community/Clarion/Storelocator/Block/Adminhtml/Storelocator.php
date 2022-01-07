<?php
/**
 * Grid container file
 * 
 * @category    Clarion
 * @package     Clarion_Storelocator
 * @author      Clarion Magento Team
 * 
 */
class Clarion_Storelocator_Block_Adminhtml_Storelocator extends Mage_Adminhtml_Block_Widget_Grid_Container
{       
  public function __construct()
  {
    /*both these variables tell magento the location of our Grid.php(grid block) file.
     * $this->_blockGroup.'/' . $this->_controller . '_grid'
     * i.e  clarion_storelocator/adminhtml_storelocator_grid
     * $_blockGroup - is your module's name.
     * $_controller - is the path to your grid block. 
     */
    $this->_controller = 'adminhtml_storelocator';
    $this->_blockGroup = 'clarion_storelocator';
    $this->_headerText = Mage::helper('clarion_storelocator')->__('Manage Stores');
    
    parent::__construct();
    
    //$this->_addButtonLabel = Mage::helper('clarion_storelocator')->__('Add New Store');
    
    if (Mage::helper('clarion_storelocator/admin')->isActionAllowed('save')) {
        $this->_updateButton('add', 'label', Mage::helper('clarion_storelocator')->__('Add New Store'));
    } else {
        $this->_removeButton('add');
    }
    
    $this->addButton(
        'flush_stores_images_cache',
        array(
            'label'      => Mage::helper('clarion_storelocator')->__('Flush Stores Images Cache'),
            'onclick'    => 'setLocation(\'' . $this->getUrl('*/*/flush') . '\')',
        )
    );
  }
}