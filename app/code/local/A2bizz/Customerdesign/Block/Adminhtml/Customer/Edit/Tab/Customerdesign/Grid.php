<?php

/**
 * MageWorx
 * Loyalty Booster Extension
 *
 * @category   MageWorx
 * @package    MageWorx_CustomerCredit
 * @copyright  Copyright (c) 2016 MageWorx (http://www.mageworx.com/)
 */
class A2bizz_Customerdesign_Block_Adminhtml_Customer_Edit_Tab_Customerdesign_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct() {
        parent::__construct();
        $this->setId('designGrid');
        $this->setDefaultSort('action_date');
        $this->setUseAjax(true);
    }
    
    protected function _getStore()
  {
	$storeId = (int) $this->getRequest()->getParam('store', 0);
	return Mage::app()->getStore($storeId);
  }

    /*public function getWebsiteOptions() {
        $options = Mage::getSingleton('adminhtml/system_store')->getWebsiteOptionHash();        
        $options[0] = Mage::helper('customerdesign')->__('Global');
        return $options;
    } */   
    
    protected function _prepareColumns() {
        $helper = Mage::helper('customerdesign');
        $this->addColumn('designersoftware_id', array(
          'header'    => Mage::helper('designersoftware')->__('Design Id'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'designersoftware_id',
	  ));
	
      $this->addColumn('style_design_code', array(
          'header'    => Mage::helper('designersoftware')->__('Design Code'),
          'align'     =>'left',
          'width'     => '50px',
          'index'     => 'style_design_code',
      ));   
 
	  
	  $this->addColumn('filename', array(
          'header'    => Mage::helper('designersoftware')->__('Design Image'),
          'align'     =>'left',
          'index'     => 'shoe_path',
          'width'     => '50px',
          'filter'    => false,
          'renderer'  => 'Inky_Designersoftware_Block_Adminhtml_Designersoftware_Renderer_Image',
      ));
      
      $store = $this->_getStore();
      $this->addColumn('total_price', array(
          'header'    => Mage::helper('designersoftware')->__('Price'),
          'align'     =>'right',
          'width'     => '10px',
          'index'     => 'total_price',
          'type' 	  => 'currency',
          'currency_code' => $store->getBaseCurrency()->getCode(),
      ));  
      
      $this->addColumn('created_time', array(
          'header'    => Mage::helper('designersoftware')->__('Created On'),          
          'index'     => 'created_time',
          'type'      => 'datetime',
          'width'     => '160px',
      )); 
      
      $this->addColumn('status', array(
          'header'    => Mage::helper('designersoftware')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));
      
      $this->addColumn('content', array(
          'header'    => Mage::helper('designersoftware')->__('Design PDF'),          
          'index'     => 'content',
          'filter'    => false,     
          'width'     => '100px',
          'renderer'  => 'Inky_Designersoftware_Block_Adminhtml_Designersoftware_Renderer_drawarea',
      ));
      
       $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('designersoftware')->__('Action'),
                'width'     => '80px',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('designersoftware')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),                        
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
                'renderer'	=> 'A2bizz_Customerdesign_Block_Adminhtml_Customer_Renderer_Action',
        ));
        
        return parent::_prepareColumns();
    }
    
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('designersoftware/designersoftware')
            ->getCollection()
            ->addFieldToFilter('customer_id',Mage::registry('current_customer')->getId());

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=> true));
    }
}
