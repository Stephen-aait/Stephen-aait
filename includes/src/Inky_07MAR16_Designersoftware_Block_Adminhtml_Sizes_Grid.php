<?php

class Inky_Designersoftware_Block_Adminhtml_Sizes_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('sizesGrid');
      $this->setDefaultSort('sizes_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('designersoftware/sizes')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('sizes_id', array(
          'header'    => Mage::helper('designersoftware')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'sizes_id',
      ));

      $this->addColumn('title', array(
          'header'    => Mage::helper('designersoftware')->__('Title'),
          'align'     =>'left',
          'index'     => 'title',
      ));
      
      $this->addColumn('price', array(
          'header'    => Mage::helper('designersoftware')->__('Price'),
          'align'     =>'left',
          'index'     => 'price',
      ));

	 $this->addColumn('filename', array(
          'header'   	=> Mage::helper('designersoftware')->__('Sizes Image'),
          'align'     	=>'left',
          'index'     	=> 'filename',
          'filter'		=>false,
          'sortable'	=>false,
          'width'		=>50,
          'renderer'	=> 'Inky_Designersoftware_Block_Adminhtml_Sizes_Renderer_Image',
      ));
      
      $this->addColumn('sort_order', array(
          'header'    => Mage::helper('designersoftware')->__('Sort Order'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'sort_order',
          'renderer'  => 'Inky_Designersoftware_Block_Adminhtml_Sizes_Renderer_Sort',
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
        $this->setMassactionIdField('sizes_id');
        $this->getMassactionBlock()->setFormFieldName('sizes');

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
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}
