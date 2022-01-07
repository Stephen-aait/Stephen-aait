<?php
/**
 * Manage Storelocator grid block
 * 
 * @category    Clarion
 * @package     Clarion_Storelocator
 * @author      Clarion Magento Team
 * 
 */
class Clarion_Storelocator_Block_Adminhtml_Storelocator_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        /** This set’s the ID of our grid i.e the html id attribute of the <div>.
         * If you’re using multiple grids in a page then id needs to be unique.
         */
        $this->setId('StorelocatorGrid');
        
        /**
         * This tells which sorting column to use in our grid. Which column 
         * should be used for default sorting
         */
        $this->setDefaultSort('storelocator_id');
        
        /**
         * The default sorting order, ascending or descending
         */
        $this->setDefaultDir('DESC');
        
        /**
         * this basically sets your grid operations in session. Example, if we 
         * were on page2 of grid or we had searched something on grid when 
         * refreshing or coming back to the page, the grid operations will 
         * still be there. It won’t revert back to its default form. 
         */
       $this->setSaveParametersInSession(true);
       $this->setUseAjax(true);
    }
    
    /**
     * Prepare storelocator grid collection object
     *
     * @return Clarion_Storelocator_Block_Adminhtml_Storelocator_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('clarion_storelocator/storelocator')->getCollection();
        /* @var $collection Clarion_Storelocator_Model_Resource_Storelocator_Collection */
        $this->setCollection($collection);
        //echo $collection->getSelect();
        return parent::_prepareCollection();
    }
    
    /**
     * Prepare default grid column
     *
     * @return Clarion_Storelocator_Block_Adminhtml_Storelocator_Grid
     */
    protected function _prepareColumns()
    {
       /**
        * ‘id’ an unique id for column
        * ‘header’ is the name of the column
        * ‘index’ is the field from our collection. This ‘id’ column needs to be 
        * present in our collection’s models.
        */
        
        $enableDisable = Mage::getModel('clarion_storelocator/enabledisable')->toArray();
        
        $this->addColumn('name', array(
            'header'=>Mage::helper('clarion_storelocator')->__('Store Name'),
            'sortable'=>true,
            'index'=>'name'
        ));
		
        $this->addColumn('country', array(
            'header'=>Mage::helper('clarion_storelocator')->__('Country'),
            'sortable'=>true,
            'index'=>'country',
            'type' => 'country',
        ));
        
        $this->addColumn('state', array(
            'header'=>Mage::helper('clarion_storelocator')->__('State'),
            'sortable'=>true,
            'index'=>'state'
        ));
        
        $this->addColumn('city', array(
            'header'=>Mage::helper('clarion_storelocator')->__('City'),
            'sortable'=>true,
            'index'=>'city'
        ));
        
        $this->addColumn('zipcode', array(
            'header'=>Mage::helper('clarion_storelocator')->__('Zipcode'),
            'sortable'=>true,
            'index'=>'zipcode'
        ));
        
        /**
         * _isExport flag used to show columns only in csv export not in the grid
         */
        if(!$this->_isExport) {
            /**
             * Check is single store mode
             */
            if (!Mage::app()->isSingleStoreMode()) {
                $this->addColumn('store_id', array(
                    'header'        => Mage::helper('clarion_storelocator')->__('Store View'),
                    'index'         => 'store_id',
                    'type'          => 'store',
                    'store_all'     => true,
                    'store_view'    => true,
                    'sortable'      => false,
                    'filter_condition_callback'
                                    => array($this, '_filterStoreCondition'),
                ));
            }
        } else {
            $this->addColumn('store_id', array(
            'header'=> Mage::helper('clarion_storelocator')->__('Store View [ admin - All Store Views ]'),
            'index' => 'store_id',
            'renderer'  => 'Clarion_Storelocator_Block_Adminhtml_Storelocator_Renderer_Store',
            ));
        }
        
        $this->addColumn('status', array(
            'header'=>Mage::helper('clarion_storelocator')->__('Status'),
            'index'     => 'status',
            'width'=>'100px',
            'type'      => 'options',
            'options'    => $enableDisable,
        ));
        
        /**
         * Adding Different Options To Grid Rows
         */
       $this->addColumn('action',
        array(
            'header'    => Mage::helper('clarion_storelocator')->__('Action'),
            'type'      => 'action',
            'getter'    => 'getId',
            'actions'   => array(
                array(
                    'caption' => Mage::helper('clarion_storelocator')->__('Edit'),
                    'url'     => array('base'=> '*/*/edit'),
                    'field'   => 'storelocator_id'
                )
            ),
            'filter'    => false,
            'sortable'  => false,
            'index'     => 'stores',
            'is_system' => true,
       ));
       
       /**
        * columns for export csv
        */
       if($this->_isExport) {
        $this->addColumn('street_address', array(
             'header'=>Mage::helper('clarion_storelocator')->__('Street Address'),
             'index'=>'street_address'
         ));
       } 
       
       if($this->_isExport) {
        $this->addColumn('phone', array(
             'header'=>Mage::helper('clarion_storelocator')->__('Phone'),
             'index'=>'phone'
         ));
       } 
       
       if($this->_isExport) {
        $this->addColumn('fax', array(
             'header'=>Mage::helper('clarion_storelocator')->__('Fax'),
             'index'=>'fax'
         ));
       } 
       
       if($this->_isExport) {
        $this->addColumn('url', array(
             'header'=>Mage::helper('clarion_storelocator')->__('URL'),
             'index'=>'url'
         ));
       } 
       
       if($this->_isExport) {
        $this->addColumn('email', array(
             'header'=>Mage::helper('clarion_storelocator')->__('Email'),
             'index'=>'email'
         ));
       } 
       
       if($this->_isExport) {
        $this->addColumn('store_logo', array(
             'header'=>Mage::helper('clarion_storelocator')->__('Store Logo'),
             'index'=>'store_logo'
         ));
       }
       
       if($this->_isExport) {
        $this->addColumn('trading_hours', array(
             'header'=>Mage::helper('clarion_storelocator')->__('Trading Hours'),
             'index'=>'trading_hours'
         ));
       }
       
       if($this->_isExport) {
        $this->addColumn('radius', array(
             'header'=>Mage::helper('clarion_storelocator')->__('Radius'),
             'index'=>'radius'
         ));
       }
       
       if($this->_isExport) {
        $this->addColumn('latitude', array(
             'header'=>Mage::helper('clarion_storelocator')->__('Latitude'),
             'index'=>'latitude'
         ));
       }
       
       if($this->_isExport) {
        $this->addColumn('longitude', array(
             'header'=>Mage::helper('clarion_storelocator')->__('Longitude'),
             'index'=>'longitude'
         ));
       }
       
       if($this->_isExport) {
        $this->addColumn('zoom_level', array(
             'header'=>Mage::helper('clarion_storelocator')->__('Zoom Level'),
             'index'=>'zoom_level'
         ));
       }
       
       if($this->_isExport) {
        $this->addColumn('description', array(
             'header'=>Mage::helper('clarion_storelocator')->__('Description'),
             'index'=>'description'
         ));
       }
       
      //Import Export functionality
      $this->addExportType('*/*/exportCsv', Mage::helper('clarion_storelocator')->__('CSV'));
      $this->addExportType('*/*/exportXml', Mage::helper('clarion_storelocator')->__('XML'));
      
        return parent::_prepareColumns();
    }
    
    /**
     * Row click url. 
     * when user click on any rows of the grid it goes to a specific URL.
     * URL is of the editAction of your controller and it passed the row’s id as a parameter. 
     * @param object $row Data row object
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('storelocator_id' => $row->getId()));
    }
    
    /**
     * Mass Actions. 
     * 
     * These used basically to do operations on multiple rows together.
     */
    protected function _prepareMassaction()
    {
        /**
         * id is the database column that serves as the unique identifier throughout 
         * your data structure, including: db table, single product magento model
         * , and the collection.
         */
        $this->setMassactionIdField('storelocator_id');
        
        /**
         * By using this we can set name of checkbox, used for selection. Which 
         * is used to pass all the ids to the controller.
         */
        $this->getMassactionBlock()->setFormFieldName('storelocatorIds');
        
        /**
         * url - sets url for the delete action
         * confirm - This shows the user a confirm dialog before submitting the URL
         */
        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('clarion_storelocator')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('clarion_storelocator')->__('Are you sure?')
        ));
        
        /**
         * Get the enable disable drop down array
         */
        $enableDisable = Mage::getModel('clarion_storelocator/enabledisable')->toOptionArray();
        
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('clarion_storelocator')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('clarion_storelocator')->__('Status'),
                         'values' => $enableDisable
                    )
              )
        ));
        return $this;
    }
    
    /**
     * Used for Ajax Based Grid
     * 
     * URL which is called in the Ajax Request, to the get
     *  the content of the grid. _current Uses the current module, controller, 
     * action and parameters.
     *
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
    
    /**
     * This allows us to filter the grid on the store view.
     *
     */
    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addStoreFilter($value);
    }
    
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}