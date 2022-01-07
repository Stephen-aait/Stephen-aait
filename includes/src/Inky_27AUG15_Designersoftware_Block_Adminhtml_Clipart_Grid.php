<?php

class Inky_Designersoftware_Block_Adminhtml_Clipart_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {	  
	  parent::__construct();
      $this->setId('clipartGrid');
      $this->setDefaultSort('clipart_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);      
  }

  protected function _prepareCollection()
  {	  
      $collection = Mage::getModel('designersoftware/clipart')->getCollection();    
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('clipart_id', array(
          'header'    => Mage::helper('designersoftware')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'clipart_id',
      ));

      $this->addColumn('title', array(
          'header'    => Mage::helper('designersoftware')->__('Title'),
          'align'     =>'left',
          'index'     => 'title',
      ));
      
      $clipartCategory = Mage::helper('designersoftware')->getClipartCategory();
      $this->addColumn('clipart_category_id', array(
          'header'    => Mage::helper('designersoftware')->__('Category'),
          'align'     =>'left',
          'index'     => 'clipart_category_id',
          'type'      => 'options',
          'options'   => $clipartCategory,          
      ));

	   $this->addColumn('filename', array(
          'header'   	=> Mage::helper('designersoftware')->__('Image'),
          'align'     	=>'left',
          'index'     	=> 'filename',
          'filter'		=>false,
          'sortable'	=>false,
          'width'		=>50,
          'renderer'	=> 'Inky_Designersoftware_Block_Adminhtml_Clipart_Renderer_Image',
      ));
      
      $this->addColumn('colorable', array(
          'header'    => Mage::helper('designersoftware')->__('Colorable'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'colorable',
          'type'      => 'options',
          'options'   => array(
              0 => 'No',
              1 => 'Yes',
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
        $this->setMassactionIdField('clipart_id');
        $this->getMassactionBlock()->setFormFieldName('clipart');

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
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}
