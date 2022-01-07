<?php

class Inky_Designersoftware_Block_Adminhtml_Color_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('colorGrid');
      $this->setDefaultSort('color_id');
      $this->setDefaultDir('ASC');
      $this->setDefaultLimit(200);
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('designersoftware/color')->getCollection();
      //echo '<pre>';print_r($collection->getData());exit;
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('color_id', array(
          'header'    => Mage::helper('designersoftware')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'color_id',
      ));

      $this->addColumn('title', array(
          'header'    => Mage::helper('designersoftware')->__('Color Title'),
          'align'     =>'left',
          'index'     => 'title',
      ));
      
     $this->addColumn('colorcode', array(
          'header'    => Mage::helper('designersoftware')->__('Color Code'),
          'align'     =>'left',
          'index'     => 'colorcode',
          'renderer'=>'Inky_Designersoftware_Block_Adminhtml_Color_Renderer_Color',
      ));

	  /*
      $this->addColumn('content', array(
			'header'    => Mage::helper('designersoftware')->__('Item Content'),
			'width'     => '150px',
			'index'     => 'content',
      ));
	  */
      
      $this->addColumn('clip_status', array(
          'header'    => Mage::helper('designersoftware')->__('Clipart Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'clip_status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));
      
      
      $this->addColumn('text_status', array(
          'header'    => Mage::helper('designersoftware')->__('Text Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'text_status',
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
        $this->setMassactionIdField('color_id');
        $this->getMassactionBlock()->setFormFieldName('color');

        /*$this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('designersoftware')->__('Delete'),
             'url'      => $this->getUrl('//massDelete'),
             'confirm'  => Mage::helper('designersoftware')->__('Are you sure?')
        ));*/

        $statuses = Mage::getSingleton('designersoftware/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        
        $this->getMassactionBlock()->addItem('update_attributes', array(
             'label'    => Mage::helper('designersoftware')->__('Update Attributes'),
             'url'      => $this->getUrl('*/*/updateAttributes'),             
        ));
        
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
