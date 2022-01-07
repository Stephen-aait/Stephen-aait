<?php

class Inky_Designersoftware_Block_Adminhtml_Clipart_Upload_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('clipart_uploadGrid');
      $this->setDefaultSort('clipart_upload_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('designersoftware/clipart_upload')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('clipart_upload_id', array(
          'header'    => Mage::helper('designersoftware')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'clipart_upload_id',
      ));
      
      $clipartCustomer = Mage::helper('designersoftware/customer')->getClipartCustomer();
      $this->addColumn('customer_id', array(
          'header'    => Mage::helper('designersoftware')->__('Customer'),
          'align'     =>'left',
          'index'     => 'customer_id',
          'type'      => 'options',
          'options'   => $clipartCustomer,          
      )); 
      
      $this->addColumn('title', array(
          'header'    => Mage::helper('designersoftware')->__('Title'),
          'align'     =>'left',          
          'index'     => 'title',
      ));    
      
       $this->addColumn('filename', array(
          'header'   	=> Mage::helper('designersoftware')->__('Image'),
          'align'     	=> 'left',
          'index'     	=> 'filename',
          'filter'		=> false,
          'sortable'	=> false,
          'width'		=> 200,
          'renderer'	=> 'Inky_Designersoftware_Block_Adminhtml_Clipart_Upload_Renderer_Image',
      ));

       /*$this->addColumn('title', array(
          'header'    => Mage::helper('designersoftware')->__('Title'),
          'align'     =>'left',
          'index'     => 'title',
      ));

	 
      $this->addColumn('content', array(
			'header'    => Mage::helper('designersoftware')->__('Item Content'),
			'width'     => '150px',
			'index'     => 'content',
      ));
	  
	  
	  $this->addColumn('sort_order', array(
          'header'    => Mage::helper('designersoftware')->__('Sort Order'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'sort_order',
          'renderer'  => 'Inky_Designersoftware_Block_Adminhtml_Clipart_Category_Renderer_Sort',
      ));*/
	  

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
                'width'     => '100',
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
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('designersoftware')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('designersoftware')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('clipart_upload_id');
        $this->getMassactionBlock()->setFormFieldName('clipart_upload');

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
        
        /*$this->getMassactionBlock()->addItem('sort_order', array(
             'label'    => Mage::helper('designersoftware')->__('Save sort order'),
             'url'      => $this->getUrl('//massSorting'),             
             //'confirm'  => Mage::helper('fonts')->__('Are you sure?'),             
        ));*/
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}
