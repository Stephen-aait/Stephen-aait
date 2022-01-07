<?php

class Inky_Designersoftware_Block_Adminhtml_Parts_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('partsGrid');
      $this->setDefaultSort('parts_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('designersoftware/parts')->getCollection(); 
      $stores = Mage::helper('designersoftware/store')->getAllStores();  
                
      foreach($stores as $store):		
      
		$storeId=$store['store_id'];		
		$sql="SELECT title FROM inky_store_parts AS o WHERE o.parts_id = `main_table`.parts_id AND o.store_id=$storeId";
		
		$expr = new Zend_Db_Expr('(' . $sql . ')'); 
		$collection->getSelect()->from(null, array('title[' . $storeId . ']'=>$expr));
		
      endforeach;      
      
      //echo '<pre>';print_r($collection->getData());exit;
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('parts_id', array(
          'header'    => Mage::helper('designersoftware')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'parts_id',
      ));
      
      /*
      $this->addColumn('title', array(
          'header'    => Mage::helper('designersoftware')->__('Title'),
          'align'     =>'left',
          'index'     => 'title',                
	  ));
	  */
	  	
	  $stores = Mage::helper('designersoftware/store')->getAllStores();
      foreach($stores as $store):
		
		$storeId	= $store['store_id'];
		$storeLabel = $store['label'];	  
		
		$this->addColumn('title['.$storeId.']', array(
          'header'    => Mage::helper('designersoftware')->__('Title '.$storeLabel),
          'align'     =>'left',
          'index'     => 'title['.$storeId.']', 
          'sortable'  => true,         
          'filter_condition_callback' => array($this, '_filterHasStoreTitleCallback'.$storeId),
		));
		
      endforeach;    

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
  
  public function _filterHasStoreTitleCallback1($collection, $column){
		Mage::log($column, null, 'designersoftware.log');
		//echo '<pre>';print_r($column);exit;
		if (!$value = $column->getFilter()->getValue()) {
			return $this;
		}
		
		if (!empty($value)) {			
			
			$this->getCollection()->getSelect()->where("title[1] like ?", "%$value%");
			Mage::log($value,null,'designersoftware.log');
		}        
		return $this;
	}
	
	public function _filterHasStoreTitleCallback2($collection, $column){
		Mage::log($column, null, 'designersoftware.log');
		//echo '<pre>';print_r($column);exit;
		if (!$value = $column->getFilter()->getValue()) {
			return $this;
		}
		
		if (!empty($value)) {			
			$this->getCollection()->getSelect()->where("title[2] like ?", "%$value%");
			Mage::log($value,null,'designersoftware.log');
		}        
		return $this;
	}

    protected function _prepareMassaction(){
        //$this->setMassactionIdField('parts_id');
        $this->getMassactionBlock()->setFormFieldName('parts');

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
