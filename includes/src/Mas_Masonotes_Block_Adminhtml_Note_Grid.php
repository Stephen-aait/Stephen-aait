<?php
/**
 * Mas_Masonotes extension by Makarovsoft.com
 * 
 * @category   	Mas
 * @package		Mas_Masonotes
 * @copyright  	Copyright (c) 2014
 * @license		http://makarovsoft.com/license.txt
 * @author		makarovsoft.com
 */
/**
 * Note admin grid block
 *
 * @category	Mas
 * @package		Mas_Masonotes
 * 
 */
class Mas_Masonotes_Block_Adminhtml_Note_Grid extends Mage_Adminhtml_Block_Widget_Grid{
	/**
	 * constructor
	 * @access public
	 * @return void
	 * 
	 */
	public function __construct(){
		parent::__construct();
		$this->setId('noteGrid');
		$this->setDefaultSort('created_at');
		$this->setDefaultDir('DESC');
		$this->setSaveParametersInSession(true);
		$this->setUseAjax(true);
	}
	/**
	 * prepare collection
	 * @access protected
	 * @return Mas_Masonotes_Block_Adminhtml_Note_Grid
	 * 
	 */
	protected function _prepareCollection(){
		$collection = Mage::getModel('masonotes/note')->getCollection();
		$this->setCollection($collection);
		$collection->getSelect()->joinLeft(
			array('nc'=>$collection->getTable('masonotes/note_customer')),
			'nc.note_id=main_table.entity_id',
			array('count(nc.rel_id) as applicable, order_id as order_id')
		)->group('main_table.entity_id');
		
		$collection->getSelect()->joinLeft(
			array('fo'=>$collection->getTable('sales/order')),
			'fo.entity_id=nc.order_id',
			array('fo.increment_id')
		);
		
		return parent::_prepareCollection();
	}
	/**
	 * prepare grid collection
	 * @access protected
	 * @return Mas_Masonotes_Block_Adminhtml_Note_Grid
	 * 
	 */
	protected function _prepareColumns(){
		$this->addColumn('entity_id', array(
			'header'	=> Mage::helper('masonotes')->__('Id'),
			'index'		=> 'entity_id',
			'type'		=> 'number'
		));
		
		$this->addColumn('increment_id', array(
			'header'	=> Mage::helper('masonotes')->__('Order ID'),
			'index'		=> 'increment_id',
			'type'		=> 'text',
			'renderer'  => 'Mas_Masonotes_Block_Adminhtml_Note_Renderer_Url'
		));
		
		$this->addColumn('note', array(
			'header'=> Mage::helper('masonotes')->__('Note'),
			'index' => 'note',
			'width' => '400px',
			'truncate' => 250,
			'type'	 	=> 'text',
		));
		
		$this->addColumn('applicable', array(
			'header'=> Mage::helper('masonotes')->__('Orders Count'),
			'index' => 'applicable',
			'filter' => false,
			'width' => '100px',
			'type'	 	=> 'text',
		));

		$this->addColumn('user_id', array(
			'header'=> Mage::helper('masonotes')->__('Posted By'),
			'index' => 'user_id',
			'width' => '200px',
			'type'	 	=> 'options',
			'options' => Mage::helper('masonotes')->getAdmins()
		));

		$this->addColumn('status', array(
			'header'	=> Mage::helper('masonotes')->__('Visible'),
			'index'		=> 'status',
			'type'		=> 'options',
			'width'     => '50px',
			'options'	=> array(
				'1' => Mage::helper('masonotes')->__('Yes'),
				'0' => Mage::helper('masonotes')->__('No'),
			)
		));
		if (!Mage::app()->isSingleStoreMode()) {
			$this->addColumn('store_id', array(
				'header'=> Mage::helper('masonotes')->__('Store Views'),
				'index' => 'store_id',
				'type'  => 'store',
				'store_all' => true,
				'store_view'=> true,
				'sortable'  => false,
				'filter_condition_callback'=> array($this, '_filterStoreCondition'),
			));
		}
		$this->addColumn('created_at', array(
			'header'	=> Mage::helper('masonotes')->__('Created at'),
			'index' 	=> 'created_at',
			'width' 	=> '120px',
			'type'  	=> 'datetime',
		));
		$this->addColumn('updated_at', array(
			'header'	=> Mage::helper('masonotes')->__('Updated at'),
			'index' 	=> 'updated_at',
			'width' 	=> '120px',
			'type'  	=> 'datetime',
		));
		$this->addColumn('action',
			array(
				'header'=>  Mage::helper('masonotes')->__('Action'),
				'width' => '100',
				'type'  => 'action',
				'getter'=> 'getId',
				'actions'   => array(
					array(
						'caption'   => Mage::helper('masonotes')->__('Edit'),
						'url'   => array('base'=> '*/*/edit'),
						'field' => 'id'
					)
				),
				'filter'=> false,
				'is_system'	=> true,
				'sortable'  => false,
		));
		$this->addExportType('*/*/exportCsv', Mage::helper('masonotes')->__('CSV'));
		$this->addExportType('*/*/exportExcel', Mage::helper('masonotes')->__('Excel'));
		$this->addExportType('*/*/exportXml', Mage::helper('masonotes')->__('XML'));
		return parent::_prepareColumns();
	}
	/**
	 * prepare mass action
	 * @access protected
	 * @return Mas_Masonotes_Block_Adminhtml_Note_Grid
	 * 
	 */
	protected function _prepareMassaction(){
		$this->setMassactionIdField('entity_id');
		$this->getMassactionBlock()->setFormFieldName('note');
		$this->getMassactionBlock()->addItem('delete', array(
			'label'=> Mage::helper('masonotes')->__('Delete'),
			'url'  => $this->getUrl('*/*/massDelete'),
			'confirm'  => Mage::helper('masonotes')->__('Are you sure?')
		));
		$this->getMassactionBlock()->addItem('status', array(
			'label'=> Mage::helper('masonotes')->__('Change Visibility'),
			'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
			'additional' => array(
				'status' => array(
						'name' => 'status',
						'type' => 'select',
						'class' => 'required-entry',
						'label' => Mage::helper('masonotes')->__('Visible to customer'),
						'values' => array(
								'1' => Mage::helper('masonotes')->__('Yes'),
								'0' => Mage::helper('masonotes')->__('No'),
						)
				)
			)
		));
		return $this;
	}
	/**
	 * get the row url
	 * @access public
	 * @param Mas_Masonotes_Model_Note
	 * @return string
	 * 
	 */
	public function getRowUrl($row){
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}
	/**
	 * get the grid url
	 * @access public
	 * @return string
	 * 
	 */
	public function getGridUrl(){
		return $this->getUrl('*/*/grid', array('_current'=>true));
	}
	/**
	 * after collection load
	 * @access protected
	 * @return Mas_Masonotes_Block_Adminhtml_Note_Grid
	 * 
	 */
	protected function _afterLoadCollection(){
		$this->getCollection()->walk('afterLoad');
		parent::_afterLoadCollection();
	}
	/**
	 * filter store column
	 * @access protected
	 * @param Mas_Masonotes_Model_Resource_Note_Collection $collection
	 * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
	 * @return Mas_Masonotes_Block_Adminhtml_Note_Grid
	 * 
	 */
	protected function _filterStoreCondition($collection, $column){
		if (!$value = $column->getFilter()->getValue()) {
        	return;
		}
		$collection->addStoreFilter($value);
		return $this;
    }
}