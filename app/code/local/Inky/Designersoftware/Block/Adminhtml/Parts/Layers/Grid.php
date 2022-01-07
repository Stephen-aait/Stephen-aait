<?php

class Inky_Designersoftware_Block_Adminhtml_Parts_Layers_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('parts_layersGrid');
      $this->setDefaultSort('parts_layers_id');
      $this->setDefaultDir('ASC');
      $this->setDefaultLimit(200);
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('designersoftware/parts_layers')->getCollection();      
      $this->setCollection($collection);      
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('parts_layers_id', array(
          'header'    => Mage::helper('designersoftware')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'parts_layers_id',
      ));  
      
      $this->addColumn('title', array(
          'header'    => Mage::helper('designersoftware')->__('Layer Title'),
          'align'     =>'left',          
          'index'     => 'title',
      )); 

      $this->addColumn('layer_code', array(
          'header'    => Mage::helper('designersoftware')->__('Layer Code'),
          'align'     =>'left',
          'index'     => 'layer_code',
      ));
      
      
      /*$styleArr = Mage::helper('designersoftware')->getStyle();
      $this->addColumn('style_id', array(
          'header'    => Mage::helper('designersoftware')->__('Style Title'),
          'align'     =>'right',
          'width'     => '50px',
          'type'      => 'options',
          'options'   => $styleArr,
          'index'     => 'style_id',          
      ));*/
      
      
      /*
      $styleDesignArr = Mage::helper('designersoftware')->getStyleDesign();
      $this->addColumn('style_design_id', array(
          'header'    => Mage::helper('designersoftware')->__('Design Code'),
          'align'     =>'left',
          'index'     => 'style_design_id',
          'type'      => 'options',
          'options'   => $styleDesignArr,
          //'renderer'  => 'Sparx_Designersoftware_Block_Adminhtml_Parts_Layers_Renderer_Style_Design',
      ));
      
      
      $styleDesignArr = Mage::helper('designersoftware')->getStyleDesign();
      $this->addColumn('style_design_ids', array(
          'header'    => Mage::helper('designersoftware')->__('Style Design Code'),
          'align'     =>'right',
          'width'     => '50px',
          //'filter'		=>false,
          'type'      => 'options',
          'options'   => $styleDesignArr,
          'index'     => 'style_design_ids',
          'renderer'  => 'Inky_Designersoftware_Block_Adminhtml_Parts_Layers_Renderer_Style_Design',
          
          'filter_condition_callback' => array($this, '_filterHasStyleDesignConditionCallback'),
      ));*/     
      
      
      $partsStyleArr = Mage::helper('designersoftware')->getPartsStyle();
      $this->addColumn('parts_style_id', array(
          'header'    => Mage::helper('designersoftware')->__('Parts Style'),
          'align'     =>'left',
          'index'     => 'parts_style_id',
          'type'      => 'options',
          'options'   => $partsStyleArr,          
      ));
      
      /*$this->addColumn('filename', array(
		   'header'	  => Mage::helper('designersoftware')->__('Image'),
           'align'     =>'left',
           'index'     => 'filename',
           'width'     => '45px', 
           'filter'		=>false,
           'sortable'	=>false,
           'renderer'	=> 'Inky_Designersoftware_Block_Adminhtml_Parts_Layers_Renderer_Leather_Image',
      ));
      
      $leatherArr = Mage::helper('designersoftware')->getLeather();
      $this->addColumn('leather_id', array(
		   'header'	  => Mage::helper('designersoftware')->__('Leather'),
           'align'     =>'left',
           'index'     => 'leather_id',
           'type'      => 'options', 
           'options'   => $leatherArr,
           //'renderer'	=> 'Sparx_Designersoftware_Block_Adminhtml_Parts_Layers_Renderer_Leather',
      ));
      */
     
             

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
	
	protected function _filterHasStyleDesignConditionCallback($collection, $column)
	{
		if (!$value = $column->getFilter()->getValue()) {			
			return $this;
		}
		
		if (empty($value)) {			
			$this->getCollection()->getSelect()->where(
				 "main_table.style_design_ids IS NULL");
		} else {		
			$this->getCollection()->getSelect()->where(
				 "main_table.style_design_ids LIKE '%\"$value\"%'");
		}
				
		return $this;
	}

	
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('parts_layers_id');
        $this->getMassactionBlock()->setFormFieldName('parts_layers');		
		
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
        $this->getMassactionBlock()->addItem('update_attributes', array(
             'label'    => Mage::helper('designersoftware')->__('Update Attributes'),
             'url'      => $this->getUrl('*/*/updateAttributes'),             
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}
