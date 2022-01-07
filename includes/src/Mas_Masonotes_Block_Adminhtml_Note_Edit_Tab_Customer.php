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
 * Note - product relation edit block
 *
 * @category	Mas
 * @package		Mas_Masonotes
 * 
 */
class Mas_Masonotes_Block_Adminhtml_Note_Edit_Tab_Customer extends Mage_Adminhtml_Block_Widget_Grid {
	/**
	 * Set grid params
	 * @access protected
	 * @return void
	 * 
	 */
	public function __construct(){
		parent::__construct();
		$this->setId('product_grid');
		$this->setDefaultSort('position');
		$this->setDefaultDir('ASC');
		$this->setUseAjax(true);
		if ($this->getNote()->getId()) {
			$this->setDefaultFilter(array('in_products'=>1));
		}
	}
	/**
	 * prepare the product collection
	 * @access protected 
	 * @return Mas_Masonotes_Block_Adminhtml_Note_Edit_Tab_Product
	 * 
	 */
	protected function _prepareCollection() {
		
		$collection = Mage::getResourceModel('sales/order_grid_collection')
            ->addFieldToSelect('entity_id')
            ->addFieldToSelect('increment_id')
            ->addFieldToSelect('status')
            ->addFieldToSelect('customer_id')
            ->addFieldToSelect('created_at')
            ->addFieldToSelect('grand_total')
            ->addFieldToSelect('order_currency_code')
            ->addFieldToSelect('store_id')
            ->addFieldToSelect('billing_name')
            ->addFieldToSelect('shipping_name')
            ->setIsCustomerMode(true);

		if ($this->getNote()->getId()){
			$constraint = 'note_customer.note_id='.$this->getNote()->getId();
		}
		else{
			$constraint = 'note_customer.note_id=0';
		}
		
		$db = Mage::getSingleton('core/resource');
        
		$tableAlias = 'note_customer';
        $conditions = array(
        	$constraint
        );
        
        $collection->getSelect()->joinLeft(
            array($tableAlias => $db->getTableName('masonotes/note_customer')),
            implode(' AND ', $conditions),
            array("{$tableAlias}.position")
        )->group('main_table.entity_id');
        
        $tableAlias = 'sales_order_item';
        $conditions = array(
            "{$tableAlias}.order_id = main_table.entity_id",
        );
        
        $collection->getSelect()->join(
            array($tableAlias => $db->getTableName('sales/order_item')),
            implode(' AND ', $conditions),
            array("{$tableAlias}.qty_ordered")
        )->group('main_table.entity_id');
			
		$this->setCollection($collection);
		parent::_prepareCollection();
		return $this;
	}
	/**
	 * prepare mass action grid
	 * @access protected
	 * @return Mas_Masonotes_Block_Adminhtml_Note_Edit_Tab_Product
	 * 
	 */ 
	protected function _prepareMassaction(){
		return $this;
	}
	/**
	 * prepare the grid columns
	 * @access protected
	 * @return Mas_Masonotes_Block_Adminhtml_Note_Edit_Tab_Product
	 * 
	 */
	protected function _prepareColumns() {
		$this->addColumn('in_products', array(
			'header_css_class'  => 'a-center',
			'type'  => 'checkbox',
			'name'  => 'in_products',
			'values'=> $this->_getSelectedProducts(),
			'align' => 'center',
			'index' => 'entity_id'
		));
		
	 	 $this->addColumn('increment_id', array(
            'header'    => Mage::helper('customer')->__('Order #'),
            'width'     => '100',
            'index'     => 'increment_id',
        ));
        
         $this->addColumn('status', array(
            'header'    => Mage::helper('customer')->__('Order Status'),
            'width'     => '100',
            'index'     => 'status',
        ));

        $this->addColumn('created_at', array(
            'header'    => Mage::helper('customer')->__('Purchase On'),
            'index'     => 'created_at',
            'type'      => 'datetime',
        ));

        /*$this->addColumn('shipping_firstname', array(
            'header'    => Mage::helper('customer')->__('Shipped to First Name'),
            'index'     => 'shipping_firstname',
        ));

        $this->addColumn('shipping_lastname', array(
            'header'    => Mage::helper('customer')->__('Shipped to Last Name'),
            'index'     => 'shipping_lastname',
        ));*/
        $this->addColumn('billing_name', array(
            'header'    => Mage::helper('customer')->__('Bill to Name'),
            'index'     => 'billing_name',
        ));

        $this->addColumn('shipping_name', array(
            'header'    => Mage::helper('customer')->__('Shipped to Name'),
            'index'     => 'shipping_name',
        ));

        $this->addColumn('qty_ordered', array(
            'header'    => Mage::helper('customer')->__('Qty Ordered'),
            'index'     => 'qty_ordered',
            'type'      => 'number',
        ));
        
        
        $this->addColumn('grand_total', array(
            'header'    => Mage::helper('customer')->__('Order Total'),
            'index'     => 'grand_total',
            'type'      => 'currency',
            'currency'  => 'order_currency_code',
        ));
        
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'    => Mage::helper('customer')->__('Website'),
                'align'     => 'center',
                'width'     => '80px',
                'type'      => 'options',
                'options'   => Mage::getSingleton('adminhtml/system_store')->getWebsiteOptionHash(true),
                'index'     => 'store_id',
            ));
        }
	}
	/**
	 * Retrieve selected products
	 * @access protected
	 * @return array
	 * 
	 */
	protected function _getSelectedProducts() {
		$products = $this->getNoteCustomers();
		if (!is_array($products)) {
			$products = array_keys($this->getSelectedProducts());
		}
		return $products;
	}
 	/**
	 * Retrieve selected products
	 * @access protected
	 * @return array
	 * 
	 */
	public function getSelectedProducts() {
		$products = array();
		$selected = Mage::registry('current_note')->getSelectedProducts();
		if (!is_array($selected)){
			$selected = array();
		}
		return $selected;
	}
	/**
	 * get row url
	 * @access public
	 * @return string
	 * 
	 */
	public function getRowUrl($item){
		return $this->getUrl('*/sales_order/view', array(
			'order_id'=>$item->getId()
		));
	}
	/**
	 * get grid url
	 * @access public
	 * @return string
	 * 
	 */
	public function getGridUrl(){
		return $this->getUrl('*/*/customersGrid', array(
			'id'=>$this->getNote()->getId()
		));
	}
	/**
	 * get the current note
	 * @access public
	 * @return Mas_Masonotes_Model_Note
	 * 
	 */
	public function getNote(){
		return Mage::registry('current_note');
	}
	/**
	 * Add filter
	 * @access protected
	 * @param object $column
	 * @return Mas_Masonotes_Block_Adminhtml_Note_Edit_Tab_Product
	 * 
	 */
	protected function _addColumnFilterToCollection($column){
		// Set custom filter for in product flag
		if ($column->getId() == 'in_products') {
			$productIds = $this->_getSelectedProducts();
			if (empty($productIds)) {
				$productIds = 0;
			}
			if ($column->getFilter()->getValue()) {
				$this->getCollection()->addFieldToFilter('entity_id', array('in'=>$productIds));
			} 
			else {
				if($productIds) {
					$this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$productIds));
				}
			}
		} 
		else {
			parent::_addColumnFilterToCollection($column);
		}
		return $this;
	}
}