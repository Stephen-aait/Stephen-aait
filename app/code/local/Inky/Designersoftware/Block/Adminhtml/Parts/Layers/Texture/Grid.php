<?php

class Inky_Designersoftware_Block_Adminhtml_Parts_Layers_Texture_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('parts_layers_textureGrid');
      $this->setDefaultSort('parts_layers_texture_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('designersoftware/parts_layers_texture')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('parts_layers_texture_id', array(
          'header'    => Mage::helper('designersoftware')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'parts_layers_texture_id',
      ));
      
      	  
	  $partsArr = Mage::helper('designersoftware')->getParts();	
      $this->addColumn('parts_id', array(
          'header'    => Mage::helper('designersoftware')->__('Parts Title'),
          'align'     =>'left',
          'index'     => 'parts_id',
          'type'      => 'options',
          'options'   => $partsArr,
      ));
      
      $partsLayersArr = Mage::helper('designersoftware')->getPartsLayers();	
      $this->addColumn('parts_layers_id', array(
          'header'    => Mage::helper('designersoftware')->__('Layer Title'),
          'align'     =>'left',
          'index'     => 'parts_layers_id',
          'type'      => 'options',
          'options'   => $partsLayersArr,
      ));
      
      $textureArr = Mage::helper('designersoftware')->getTexture();	
      $this->addColumn('texture_id', array(
          'header'    => Mage::helper('designersoftware')->__('Texture Title'),
          'align'     =>'left',
          'index'     => 'texture_id',
          'type'      => 'options',
          'options'   => $textureArr,
      ));
      
      
      /*$this->addColumn('filename', array(
          'header'    => Mage::helper('designersoftware')->__('Texture Image'),
          'align'     => 'left',
          'index'     => 'filename',
      ));*/
      
      
     /*      
      
      $texturetypeArr = Mage::helper('designersoftware')->getLeathertype();
      $this->addColumn('texturetype_id', array(
          'header'    => Mage::helper('designersoftware')->__('Texture Type'),
          'align'     => 'left',
          'width'     => '150px',
          'index'     => 'texturetype_id',
          'type'      => 'options',
          'options'   => $texturetypeArr,
      ));      
     
	  
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
        $this->setMassactionIdField('parts_layers_texture_id');
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
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}
