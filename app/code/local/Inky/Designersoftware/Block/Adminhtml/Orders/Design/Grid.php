<?php

class Inky_Designersoftware_Block_Adminhtml_Orders_Design_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('ordersDesignGrid');
      $this->setDefaultSort('orders_design_id');
      $this->setDefaultDir('DESC');
      $this->setDefaultLimit(200);
      $this->setSaveParametersInSession(true);
  }
  
  protected function _getStore()
  {
	$storeId = (int) $this->getRequest()->getParam('store', 0);
	return Mage::app()->getStore($storeId);
  }
  
  protected function _prepareCollection()
  {
      $collection = Mage::getModel('designersoftware/orders_design')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
	  $this->addColumn('orders_design_id', array(
          'header'    => Mage::helper('designersoftware')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'orders_design_id',
      ));
      
      $this->addColumn('order_id', array(
          'header'    => Mage::helper('designersoftware')->__('Order ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'order_id',
          'renderer'  => 'Inky_Designersoftware_Block_Adminhtml_Orders_Design_Renderer_Order',
          //'filter_condition_callback' => array($this, '_filterCustomerNameConditionCallback'),
      ));
      
      /*
      $this->addColumn('designersoftware_id', array(
          'header'    => Mage::helper('designersoftware')->__('Designersoftware ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'designersoftware_id',
      ));
      */ 

	  /*$getCustomer = Mage::helper('designersoftware/customer')->getDesignerCustomer();
      $this->addColumn('customer_id', array(
          'header'    => Mage::helper('designersoftware')->__('Customer'),
          'align'     =>'left',
          'index'     => 'customer_id',
          'type'      => 'options',
          'options'   => $getCustomer, 
      ));*/
      
            
      $this->addColumn('customer_id', array(
          'header'    => Mage::helper('designersoftware')->__('Customer'),
          'align'     =>'left',
          'index'     => 'customer_id',
          'renderer'  => 'Inky_Designersoftware_Block_Adminhtml_Designersoftware_Renderer_Customer',
          'filter_condition_callback' => array($this, '_filterCustomerNameConditionCallback'),
      ));
      
      /*
      $this->addColumn('title', array(
          'header'    => Mage::helper('designersoftware')->__('Design Title'),
          'align'     =>'left',          
          'index'     => 'title',
      ));
	  */	
      	
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
      
      /*$this->addColumn('update_time', array(
          'header'    => Mage::helper('designersoftware')->__('Updated On'),          
          'index'     => 'update_time',
          'type'      => 'datetime',
          'width'     => '160px',
      ));
      
      $this->addColumn('sort_order', array(
          'header'    => Mage::helper('designersoftware')->__('Sort Order'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'sort_order',
          'renderer'  => 'Inky_Designersoftware_Block_Adminhtml_Designersoftware_Renderer_Sort',
      ));
      */ 
      
	  /*
      $this->addColumn('content', array(
			'header'    => Mage::helper('designersoftware')->__('Item Content'),
			'width'     => '150px',
			'index'     => 'content',
      ));
	  */
	  
	  /*
	  $this->addColumn('gallery_status', array(
          'header'    => Mage::helper('designersoftware')->__('Gallery Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'gallery_status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
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
      */
      
      $this->addColumn('content', array(
          'header'    => Mage::helper('designersoftware')->__('Design PDF'),          
          'index'     => 'content',
          'filter'    => false,     
          'width'     => '100px',
          'renderer'  => 'Inky_Designersoftware_Block_Adminhtml_Orders_Design_Renderer_drawarea',
      )); 
      /*
	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('designersoftware')->__('Action'),
                'width'     => '80px',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('designersoftware')->__('Edit'),
                        'url'       => array('base'=> 'star/star/edit'),                        
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
                'renderer'	=> 'Inky_Designersoftware_Block_Adminhtml_Designersoftware_Renderer_Action',
        ));     
        */  
        
		
		$this->addExportType('*/*/exportCsv', Mage::helper('designersoftware')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('designersoftware')->__('XML'));
	  
      return parent::_prepareColumns();
  }
  
  protected function _filterCustomerNameConditionCallback($collection, $column)
	{		
		if (!$value = $column->getFilter()->getValue()) {
			return $this;
		}
		
		if (empty($value)) {
			return $this;
		}
		else {
			
			$valueArr = explode(' ',$value);
			
			//echo '<pre>';print_r($valueArr);exit;
			/*$this->getCollection()->getSelect()
					->join(array('ce1' => 'customer_entity_varchar'), 'ce1.entity_id=main_table.customer_id')
					->where('ce1.value like "%'.$valueArr[0].'%"');*/
						
			/*
			if(strlen($valueArr[0])>2):
				if(!empty($valueArr[1])):
					$this->getCollection()->getSelect()
						->join(array('ce1' => 'customer_entity_varchar'), 'ce1.entity_id=main_table.customer_id')
						->where('ce1.value like "%'.$valueArr[0].'%" OR ce1.value like "%'.$valueArr[1].'%"');
				else:
					$this->getCollection()->getSelect()
						->join(array('ce1' => 'customer_entity_varchar'), 'ce1.entity_id=main_table.customer_id')
						->where('ce1.value like "%'.$valueArr[0].'%"');
				
				endif;
			endif;
			*/ 
			
			if( !empty($valueArr[0]) && !empty($valueArr[1]) ){
				//echo 'first';exit;
					$this->getCollection()->getSelect()
						->join(array('ce1' => 'customer_entity_varchar'), 'ce1.entity_id=main_table.customer_id')
						->where('ce1.value like "%'.trim($valueArr[0]).'%" OR ce1.value like "%'.trim($valueArr[1]).'%"');
			} else if( !empty($valueArr[0]) ) {
				//echo 'second';exit;
					$this->getCollection()->getSelect()
						->join(array('ce1' => 'customer_entity_varchar'), 'ce1.entity_id=main_table.customer_id')
						->where('ce1.value like "%'.$valueArr[0].'%"');
			} else {
				//echo 'third';exit;
					$this->getCollection()->getSelect()
						->join(array('ce1' => 'customer_entity_varchar'), 'ce1.entity_id=main_table.customer_id')
						->where('ce1.value like "%'.$valueArr[1].'%"');
			}
			
		}

		return $this;
	}

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('designersoftware_id');
        $this->getMassactionBlock()->setFormFieldName('designersoftware');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('designersoftware')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('designersoftware')->__('Are you sure?')
        ));
        
        $this->getMassactionBlock()->addItem('update_attributes', array(
             'label'    => Mage::helper('designersoftware')->__('Update Attributes'),
             'url'      => $this->getUrl('*/*/updateAttributes'),             
        ));
        
        $statuses = Mage::getSingleton('designersoftware/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('designersoftware')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('designersoftware')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        
        $this->getMassactionBlock()->addItem('sort_order', array(
             'label'    => Mage::helper('designersoftware')->__('Save sort order'),
             'url'      => $this->getUrl('*/*/massSorting'),             
             //'confirm'  => Mage::helper('designersoftware')->__('Are you sure?'),             
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return 'javascript:void(0)';
      //return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}
