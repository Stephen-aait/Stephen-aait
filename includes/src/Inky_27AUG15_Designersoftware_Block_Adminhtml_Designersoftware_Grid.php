<?php

class Inky_Designersoftware_Block_Adminhtml_Designersoftware_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('designersoftwareGrid');
      $this->setDefaultSort('designersoftware_id');
      $this->setDefaultDir('DESC');
      $this->setDefaultLimit(200);
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('designersoftware/designersoftware')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('designersoftware_id', array(
          'header'    => Mage::helper('designersoftware')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'designersoftware_id',
      ));

	  $clipartCustomer = Mage::helper('designersoftware/customer')->getClipartCustomer();
      $this->addColumn('customer_id', array(
          'header'    => Mage::helper('designersoftware')->__('Customer'),
          'align'     =>'left',
          'index'     => 'customer_id',
          'type'      => 'options',
          'options'   => $clipartCustomer, 
      ));
      	
      $this->addColumn('style_design_code', array(
          'header'    => Mage::helper('designersoftware')->__('Design Code'),
          'align'     =>'left',
          'index'     => 'style_design_code',
      ));   
      
	  
	  $this->addColumn('filename', array(
          'header'    => Mage::helper('designersoftware')->__('Design Image'),
          'align'     =>'left',
          'index'     => 'shoe_path',
          'width'     => '50px',
          'renderer'  => 'Inky_Designersoftware_Block_Adminhtml_Designersoftware_Renderer_Image',
      ));
      
      $this->addColumn('total_price', array(
          'header'    => Mage::helper('designersoftware')->__('Design Price'),
          'align'     =>'right',
          'width'     => '30px',
          'index'     => 'total_price',
      ));  
      
      $this->addColumn('created_time', array(
          'header'    => Mage::helper('designersoftware')->__('Created On'),          
          'index'     => 'created_time',
          'type'      => 'datetime',
          'width'     => '100px',
      )); 
      
      $this->addColumn('update_time', array(
          'header'    => Mage::helper('designersoftware')->__('Updated On'),          
          'index'     => 'update_time',
          'type'      => 'datetime',
          'width'     => '100px',
      )); 
      
	  /*
      $this->addColumn('content', array(
			'header'    => Mage::helper('designersoftware')->__('Item Content'),
			'width'     => '150px',
			'index'     => 'content',
      ));
	  */

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
	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('designersoftware')->__('Action'),
                'width'     => '150',
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
                'renderer'	=> 'Inky_Designersoftware_Block_Adminhtml_Designersoftware_Renderer_Action',
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('designersoftware')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('designersoftware')->__('XML'));
	  
      return parent::_prepareColumns();
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
        return $this;
    }

  public function getRowUrl($row)
  {
      return 'javascript:void(0)';//$this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}
