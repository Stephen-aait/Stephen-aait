<?php

/**
 * Iksanika llc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.iksanika.com/products/IKS-LICENSE.txt
 *
 * @category   Iksanika
 * @package    Iksanika_Productupdater
 * @copyright  Copyright (c) 2013 Iksanika llc. (http://www.iksanika.com)
 * @license    http://www.iksanika.com/products/IKS-LICENSE.txt
 */

class Iksanika_Productupdater_Block_Catalog_Product_Grid 
    extends Iksanika_Productupdater_Block_Widget_Grid //Mage_Adminhtml_Block_Widget_Grid
{
    protected $isenhanced = true;
    private $isenabled = true;
    
    private $currentProfile = null;

    public static $columnSettings = array();
    
    public function __construct()
    {
        parent::__construct();
        
        $this->currentProfile = Mage::helper('productupdater/profile')->getCurrentProfile();
        $this->isenabled = Mage::getStoreConfig('productupdater/general/isenabled');
        $this->isAllowed = array(
            'related' => (int)Mage::getStoreConfig('productupdater/productrelator/enablerelated') === 1,
            'cross_sell' => (int)Mage::getStoreConfig('productupdater/productrelator/enablecrosssell') === 1,
            'up_sell' => (int)Mage::getStoreConfig('productupdater/productrelator/enableupsell') === 1
        );
        
        $this->setId('productGrid');
        
        $this->prepareDefaults();
        
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
//        $this->setVarNameFilter('product_filter');
        
        self::prepareColumnSettings();
        $this->setTemplate('iksanika/productupdater/catalog/product/grid.phtml');
        $this->setMassactionBlockName('productupdater/widget_grid_massaction');
        
    }
    
    private function prepareDefaults() 
    {
        $this->setDefaultLimit(Mage::getStoreConfig('productupdater/columns/limit'));
        $this->setDefaultPage(Mage::getStoreConfig('productupdater/columns/page'));
        $this->setDefaultSort(Mage::getStoreConfig('productupdater/columns/sort'));
        $this->setDefaultDir(Mage::getStoreConfig('productupdater/columns/dir'));
//        echo '<pre>';
//        var_dump($this->currentProfile);
//        die();
//        $this->setDefaultLimit($this->currentProfile->columns_limit);
//        echo '<pre>';
//        var_dump($this->currentProfile);
/* required for new version (profiling version)
        $this->setDefaultLimit(20);
        $this->setDefaultPage($this->currentProfile->columns_page);
        $this->setDefaultSort($this->currentProfile->columns_sort);
        $this->setDefaultDir($this->currentProfile->columns_dir);
 */
    }
    
    public static function prepareColumnSettings() 
    {
        $storeSettings = Mage::getStoreConfig('productupdater/columns/showcolumns');
        /* required for new version (profiling version)
        $storeSettings = Mage::helper('productupdater/profile')->getCurrentProfile()->columns_showcolumns;
         */
        
        $tempArr = explode(',', $storeSettings);
        
        foreach($tempArr as $showCol) 
        {
            self::$columnSettings[trim($showCol)] = true;
        }
    }
    
    public static function getColumnSettings()
    {
        if(count(self::$columnSettings) == 0)
        {
            self::prepareColumnSettings();
        }
        return self::$columnSettings;
    }
    
    public static function getColumnForUpdate()
    {
        $fields = array('product');
        
        if(count(self::getColumnSettings()))
        {
            foreach(self::getColumnSettings() as $columnId => $status)
            {
//                if(isset(self::$columnType[$columnId]))
                {
                    $fields[] = $columnId;
                }
            }
        }
        return $fields;
    }
    
    public function colIsVisible($code) 
    {
        return isset(self::$columnSettings[$code]);
    }
    
    
    
    
    
    protected function _prepareLayout()
    {
        $this->setChild('save_config_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('productupdater')->__('Save Config'),
                    'onclick'   => 'doSaveConfig()',
/*                    'class'   => 'task'*/
                ))
        );
        $this->setChild('export_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('Export'),
                    'onclick'   => $this->getJsObjectName().'.doExport()',
                    'class'   => 'task'
                ))
        );
        $this->setChild('reset_filter_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
//                    'label'     => Mage::helper('adminhtml')->__(''),
                    'onclick'   => $this->getJsObjectName().'.resetFilter()',
                    'class'   => 'iks-btn iks-search-reset',
                ))
        );
        $this->setChild('search_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
//                    'label'     => Mage::helper('adminhtml')->__('Search'),
                    'onclick'   => $this->getJsObjectName().'.doFilter()',
                    'class'   => 'task iks-btn iks-search'
                ))
        );
        return Mage_Adminhtml_Block_Widget::_prepareLayout();
    }

    public function getSaveConfigButtonHtml()
    {
        return $this->getChildHtml('save_config_button');
    }

    

    protected function _addColumnFilterToCollection($column)
    {
        if ($this->getCollection()) {
            if ($column->getId() == 'websites') {
                $this->getCollection()->joinField('websites',
                    'catalog/product_website',
                    'website_id',
                    'product_id=entity_id',
                    null,
                    'left');
            }
        }
        return parent::_addColumnFilterToCollection($column);
    }
    
    protected function _setFilterValues($data)
    {
        foreach ($this->getColumns() as $columnId => $column) 
        {
            if (
                isset($data[$columnId])
                && (!empty($data[$columnId]) || strlen($data[$columnId]) > 0)
                && $column->getFilter()) 
            {
                $column->getFilter()->setValue($data[$columnId]);
                if($columnId != 'category_ids' && $columnId != 'category_ids' && $columnId != 'related_ids' && $columnId != 'cross_sell_ids' &&
                    $columnId != 'associated_configurable_ids' && $columnId != 'associated_groupped_ids')
                    $this->_addColumnFilterToCollection($column);
            }
        }
        return $this;
    }
    
    /**
     * Sets sorting order by some column
     *
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _setCollectionOrder($column)
    {
        $collection = $this->getCollection();
        if ($collection) {
            $columnIndex = $column->getFilterIndex() ?
                $column->getFilterIndex() : $column->getIndex();
            $columnIndex = ($columnIndex == 'category_ids') ? 'cat_ids' : $columnIndex;
            $collection->setOrder($columnIndex, strtoupper($column->getDir()));
        }
        return $this;
    }
    
    public function getQuery() 
    {
        return urldecode($this->getParam('q'));
    }
    
    protected function _prepareCollection()
    {
        $collection = $this->getCollection();
        $collection = !$collection ? Mage::getModel('catalog/product')->getCollection() : $collection;
        if($queryString = $this->getQuery()) 
        {
            $query = Mage::helper('catalogSearch')->getQuery();
            $query->setStoreId(Mage::app()->getStore()->getId());
            $query->setQueryText(Mage::helper('catalogsearch')->getQuery()->getQueryText());

            $collection = $query->getSearchCollection();
            $collection->addSearchFilter(Mage::helper('catalogsearch')->getQuery()->getQueryText());
            $collection->addAttributeToSelect('*');
            //$collection->addAttributeToFilter('status', 1);
        }
        $store = $this->_getStore();
        $collection 
            ->joinField(
                'qty',
                'cataloginventory/stock_item',
                'qty',
                'product_id=entity_id',
                '{{table}}.stock_id=1',
                'left')
            ->joinField(
                'is_in_stock',
                'cataloginventory/stock_item',
                'is_in_stock',
                'product_id=entity_id',
                '{{table}}.stock_id=1',
                'left')
            ->joinField(
                'cat_ids',
                'catalog/category_product',
                'category_id',
                'product_id=entity_id',
                null,
                'left')
            ->joinField(
                'category',
                'catalog/category_product',
                'category_id',
                'product_id=entity_id',
                null,
                'left')
                
            ->joinField(
                'related_ids',
                'catalog/product_link',
                'linked_product_id',
                'product_id=entity_id',
                '{{table}}.link_type_id='.Mage_Catalog_Model_Product_Link::LINK_TYPE_RELATED, // 1- relation, 4 - up_sell, 5 - cross_sell
                'left')
            ->joinField(
                'cross_sell_ids',
                'catalog/product_link',
                'linked_product_id',
                'product_id=entity_id',
                '{{table}}.link_type_id='.Mage_Catalog_Model_Product_Link::LINK_TYPE_CROSSSELL, // 1- relation, 4 - up_sell, 5 - cross_sell
                'left')
            ->joinField(
                'up_sell_ids',
                'catalog/product_link',
                'linked_product_id',
                'product_id=entity_id',
                '{{table}}.link_type_id='.Mage_Catalog_Model_Product_Link::LINK_TYPE_UPSELL, // 1- relation, 4 - up_sell, 5 - cross_sell
                'left')
            ->joinField(
                'associated_groupped_ids',
                'catalog/product_link',
                'linked_product_id',
                'product_id=entity_id',
                '{{table}}.link_type_id='.Mage_Catalog_Model_Product_Link::LINK_TYPE_GROUPED, // 1- relation, 4 - up_sell, 5 - cross_sell
                'left')
            ->joinField(
                'associated_configurable_ids',
                'catalog/product_super_link',
                'product_id',
                'parent_id=entity_id',
                null, // 1- relation, 4 - up_sell, 5 - cross_sell
                'left')
                
                
            ->joinField(
                'tier_price',
//                'catalog_product_entity_tier_price',
                Mage::getConfig()->getTablePrefix().'catalog_product_entity_tier_price',
//                Mage::getSingleton("core/resource")->getTableName('catalog_product_entity_tier_price'),
//                'catalog/product_tier_price',
                'value', 
                'entity_id=entity_id',
                null,//'{{table}}.website_id='.$store->getId(),
                'left');                
            ;
//        $collection->printLogQuery(true);
//        die();
        $collection->groupByAttribute('entity_id');

        if ($store->getId()) 
        {
            //$collection->setStoreId($store->getId());
            
            $collection->setStoreId($store->getId());
            
            $collection->addStoreFilter($store);
            $collection->joinAttribute(
                'custom_name', 
                'catalog_product/name', 
                'entity_id', 
                null, 
                'inner', 
                $store->getId()
            );
            $collection->joinAttribute(
                'name', 
                'catalog_product/name', 
                'entity_id', 
                null, 
                'inner', 
                $store->getId()
            );
            $collection->joinAttribute(
                'status', 
                'catalog_product/status', 
                'entity_id', 
                null, 
                'inner', 
                $store->getId()
            );
            $collection->joinAttribute(
                'visibility', 
                'catalog_product/visibility', 
                'entity_id', 
                null, 
                'inner', 
                $store->getId()
            );
            $collection->joinAttribute(
                'price', 
                'catalog_product/price', 
                'entity_id', 
                null, 
                'left', 
                $store->getId()
            );
        }
        else {
            $collection->addAttributeToSelect('price');
            $collection->addAttributeToSelect('status');
            $collection->addAttributeToSelect('visibility');
        }
        
        // EG: Select all needed columns.
        //id,name,type,attribute_set,sku,price,qty,visibility,status,websites,image
        foreach(self::$columnSettings as $col => $true) 
        {
            if($col == 'category_ids')
            {
                //$filter = $this->getParam('filter');
//                echo $this->getVarNameFilter().'~';
                $filter = $this->getParam($this->getVarNameFilter());
                if($filter)
                {
                    $filter_data = Mage::helper('adminhtml')->prepareFilterString($filter);
                    if(isset($filter_data['category_ids']))
                    {
                        if(trim($filter_data['category_ids'])=='')
                            continue;
                        $categoryIds = explode(',', $filter_data['category_ids']);
                        $catIdsArray = array();
                        foreach($categoryIds as $categoryId)
                        {
                            //$collection->addCategoryFilter(Mage::getModel('catalog/category')->load($categoryId));
                            $catIdsArray[] = $categoryId;
                        }
                        $collection->addAttributeToFilter('cat_ids', array( 'in' => $catIdsArray));                        
                        //$collection->printLogQuery(true);
                    }
                }
            }
            if($col == 'related_ids' || $col == 'cross_sell_ids' || $col == 'up_sell_ids' || 
                    $col == 'associated_groupped_ids' || $col == 'associated_configurable_ids')
            { 
                $filter = $this->getParam($this->getVarNameFilter());
                if($filter)
                {
                    $filter_data = Mage::helper('adminhtml')->prepareFilterString($filter);
                    if(isset($filter_data[$col]))
                    {
                        if(trim($filter_data[$col])=='')
                            continue;
                        $relatedIds = explode(',', $filter_data[$col]);
                        $relatedIdsArray = array();
                        foreach($relatedIds as $relatedId)
                        {
                            //$collection->addCategoryFilter(Mage::getModel('catalog/category')->load($categoryId));
                            $relatedIdsArray[] = intval($relatedId);
                        }
                        $collection->addAttributeToFilter($col, array( 'in' => $relatedIdsArray));                        
                    }
                }
            }
            /*
            if($col == 'sku')
            {
                $filter = $this->getParam($this->getVarNameFilter());
                if($filter)
                {
                    $filter_data = Mage::helper('adminhtml')->prepareFilterString($filter);
                    if(isset($filter_data['sku']))
                    {
                        if(trim($filter_data['sku'])=='')
                            continue;
                        $skuIds = explode(',', $filter_data['sku']);
                        $skuIdsArray = array();
                        foreach($skuIds as $skuId)
                            $skuIdsArray[] = $skuId;
                        $collection->addAttributeToFilter('sku', array( 'inset' => $skuIdsArray));                        
                    }
                }
            }
           */
            if($col == 'qty' || $col == 'websites' || $col=='id' || $col=='category_ids' || $col=='related_ids' || 
                    $col=='cross_sell_ids' || $col=='up_sell_ids' || $col=='associated_groupped_ids' || 
                    $col=='associated_configurable_ids' || $col=='group_price') 
                continue;
            else
                $collection->addAttributeToSelect($col);
        }
        
        $this->setCollection($collection);
//$collection->printLogQuery(true);
//die();
        parent::_prepareCollection();
        // if no check on _isExport - in export file will be only 20 elements instead of full 
        if(!$this->_isExport)
        {
            $collection->addWebsiteNamesToResult();
        }
//$collection->printLogQuery(true);
//die();
        
        return $this;
    }


    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }
    
    
    public function _applyMyFilter($column)
    {
        // empty filter condition to avoid standard magento conditions
    }
            
    protected function _prepareColumns()
    {
        $store = $this->_getStore();
        
        if($this->colIsVisible('id')) 
        {
            $this->addColumn('id',
                array(
                    'header'=> Mage::helper('catalog')->__('ID'),
//                    'width' => '50px',
                    'type'  => 'number',
                    'index' => 'entity_id',
            ));
        }
        
        $imgWidth = Mage::getStoreConfig('productupdater/images/width')."px";
        
        if($this->colIsVisible('thumbnail')) 
        {
            $this->addColumn('thumbnail',
                array(
                    'header'=> Mage::helper('catalog')->__('Thumbnail'),
                    'type'  => 'image',
                    'width' => $imgWidth,
                    'index' => 'thumbnail',
                    'renderer' => 'Iksanika_Productupdater_Block_Widget_Grid_Column_Renderer_Image',
            ));
        }
        
        if($this->colIsVisible('small_image')) 
        {
            $this->addColumn('small_image',
                array(
                    'header'=> Mage::helper('catalog')->__('Small Img'),
                    'type'  => 'image',
                    'width' => $imgWidth,
                    'index' => 'small_image',
                    'renderer' => 'Iksanika_Productupdater_Block_Widget_Grid_Column_Renderer_Image',
            ));
        }
        
        if($this->colIsVisible('image')) 
        {
            $this->addColumn('image',
                array(
                    'header'=> Mage::helper('catalog')->__('Image'),
                    'type'  => 'image',
                    'width' => $imgWidth,
                    'index' => 'image',
                    'renderer' => 'Iksanika_Productupdater_Block_Widget_Grid_Column_Renderer_Image',
            ));
        }
        
        if($this->colIsVisible('name')) 
        {
            $this->addColumn('name',
                array(
                    'header'=> Mage::helper('catalog')->__('Name'),
                    'type' => 'input',
                    'name' => 'pu_name[]',
//                    'min-width' => '250px',
//                    'renderer' => 'Iksanika_Productupdater_Block_Widget_Grid_Column_Renderer_Input',
                    'index' => 'name'/*,
                    'width' => '150px'*/
            ));
        }
        if($this->colIsVisible('name')) 
        {
            if ($store->getId()) 
            {
                $this->addColumn('custom_name',
                    array(
                        'header'=> Mage::helper('catalog')->__('Name In %s', $store->getName()),
                        'index' => 'custom_name',
                        'width' => '150px'
                ));
            }
        }

        if($this->colIsVisible('type_id')) 
        {
            $this->addColumn('type',
                array(
                    'header'=> Mage::helper('catalog')->__('Type'),
//                    'width' => '60px',
                    'index' => 'type_id',
                    'type'  => 'options',
                    'options' => Mage::getSingleton('catalog/product_type')->getOptionArray(),
            ));
        }
        
        if($this->colIsVisible('attribute_set_id')) 
        {
            $sets = Mage::getResourceModel('eav/entity_attribute_set_collection')
                ->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId())
                ->load()
                ->toOptionHash();
            
            $this->addColumn('attribute_set_id',
                array(
                    'header'=> Mage::helper('catalog')->__('Attrib. Set Name'),
//                    'width' => '100px',
                    'index' => 'attribute_set_id',
                    'type'  => 'options',
                    'options' => $sets,
                    'renderer' => "Iksanika_Productupdater_Block_Widget_Grid_Column_Renderer_Options",
            ));
        }
        
        if($this->colIsVisible('sku')) 
        {
            $this->addColumn('sku',
                array(
                    'header'=> Mage::helper('catalog')->__('SKU'),
//                    'width' => '80px',
                    'index' => 'sku',
                    'name' => 'pu_sku[]',
                    'filter' => 'Iksanika_Productupdater_Block_Widget_Grid_Column_Filter_Sku',
                    'renderer' => 'Iksanika_Productupdater_Block_Widget_Grid_Column_Renderer_Input',
                    'type' => 'input'
            ));
        }
        
        if($this->colIsVisible('category_ids')) 
        {
            $this->addColumn('category_ids',
                array(
                    'header'=> Mage::helper('catalog')->__('Category ID\'s'),
//                    'width' => '80px',
                    'index' => 'category_ids',
                    'name' => 'pu_category_ids[]',
                    'renderer' => 'Iksanika_Productupdater_Block_Widget_Grid_Column_Renderer_Input',
                    'type' => 'input'
            ));
        }
        
        if($this->colIsVisible('category')) 
        {
            $this->addColumn('category',
                array(
                    'header' => Mage::helper('catalog')->__('Categories'),
                    'index' => 'category',
                    'renderer' => 'Iksanika_Productupdater_Block_Widget_Grid_Column_Renderer_Category',
//                    'sortable' => false,
                    'filter' => 'Iksanika_Productupdater_Block_Widget_Grid_Column_Filter_Category',
                    'type' => 'options',
                    'options' => Mage::helper('productupdater/category')->getOptionsForFilter(),
            ));
        }


        if($this->colIsVisible('price')) 
        {
            $this->addColumn('price',
                array(
                    'header'=> Mage::helper('catalog')->__('Price'),
//                    'type'  => 'input',
                    'type'  => 'price',
                    'currency_code' => $store->getBaseCurrency()->getCode(),
                    'index' => 'price',
                    'name' => 'pu_price[]',
                    'renderer' => 'Iksanika_Productupdater_Block_Widget_Grid_Column_Renderer_Price',
                    //'currency_code' => $currency
            ));
        }

        if($this->colIsVisible('qty')) 
        {
            $this->addColumn('qty',
                array(
                    'header'=> Mage::helper('catalog')->__('Qty'),
//                    'width' => '100px',
                    'type'  => 'input',
                    'index' => 'qty',
                    'name' => 'pu_qty[]',
                    'filter' => 'Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Range',
                    'renderer' => 'Iksanika_Productupdater_Block_Widget_Grid_Column_Renderer_Number',
            ));
        }

        if($this->colIsVisible('is_in_stock')) 
        {
            $columnIsInStock = array(
                'header' => Mage::helper('catalog')->__('Is in Stock'),
                'type' => 'options', 
                'index' => 'is_in_stock',
                'name' => 'is_in_stock',
                'options' => array(0 => __('No'), 1 => __('Yes')),
            );
            if(!Mage::getStoreConfig('productupdater/stockmanage/autoStockStatus'))
            {
                $columnIsInStock['renderer'] = 'Iksanika_Productupdater_Block_Widget_Grid_Column_Renderer_Options';
            }
            $this->addColumn('is_in_stock', $columnIsInStock);
        }
        
        if($this->colIsVisible('visibility')) 
        {
            $this->addColumn('visibility',
                array(
                    'header'=> Mage::helper('catalog')->__('Visibility'),
//                    'width' => '70px',
                    'index' => 'visibility',
//                    'type'  => 'iks_options',
                    'type'  => 'options',
                    'options' => Mage::getModel('catalog/product_visibility')->getOptionArray(),
                    'renderer' => 'Iksanika_Productupdater_Block_Widget_Grid_Column_Renderer_Options',
            ));
        }

        if($this->colIsVisible('status')) 
        {
            $this->addColumn('status',
                array(
                    'header'=> Mage::helper('catalog')->__('Status'),
//                    'width' => '70px',
                    'index' => 'status',
//                    'type'  => 'iks_options',
                    'type'  => 'options',
                    'options' => Mage::getSingleton('catalog/product_status')->getOptionArray(),
                    'renderer' => 'Iksanika_Productupdater_Block_Widget_Grid_Column_Renderer_Options',
            ));
        }

        if($this->colIsVisible('websites')) 
        {
            if (!Mage::app()->isSingleStoreMode()) {
                $this->addColumn('websites',
                    array(
                        'header'=> Mage::helper('catalog')->__('Websites'),
//                        'width' => '100px',
                        'sortable'  => false,
                        'index'     => 'websites',
                        'type'      => 'options',
                        'options'   => Mage::getModel('core/website')->getCollection()->toOptionHash(),
                ));
            }
        }

        $ignoreCols = array(
            'id'=>true, 
            'websites'=>true,
            'status'=>true,
            'visibility'=>true,
            'qty'=>true,
            'is_in_stock'=>true,
            'price'=>true,
            'sku'=>true,
            'attribute_set_id'=>true, 
            'type_id'=>true,
            'name'=>true, 
            'image'=>true, 
            'thumbnail' => true, 
            'small_image'=>true,
            'category_ids' => true,
            'category' => true,
        );
        
        $currency = $store->getBaseCurrency()->getCode();
        
        $taxClassCollection = Mage::getModel('tax/class_source_product')->toOptionArray();
        $taxClasses = array();
        foreach($taxClassCollection as $taxClassItem)
            $taxClasses[$taxClassItem['value']] = $taxClassItem['label'];


        
        $storeId    =  (int) Mage::app()->getRequest()->getParam('store', 0);
        $store  =   Mage::app()->getStore($storeId);
        
        
        $attributes = Mage::helper('productupdater')->getAttributesList();
        
        $typeList = array();
        
        foreach($attributes as $attribute)
        {
            $typeList[$attribute->getFrontendInput()] = true;
        }
        
        // associate array (hashmap) attribute code -> attribute
        $attrs    =   array();
        foreach($attributes as $attribute)
        {
            $attrs[$attribute->getAttributeCode()]  =   $attribute;
        }
        
        
        foreach(self::$columnSettings as $code => $true) 
        {
            $column = array();
            
            if(isset($ignoreCols[$code])) 
                continue;
            
            if($code != 'related_ids' && $code != 'cross_sell_ids' && $code != 'up_sell_ids' && 
                $code != 'associated_groupped_ids' && $code != 'associated_configurable_ids')
            {
                $column['index']    =   $code;
                $column['header']   =   Mage::helper('catalog')->__($attrs[$code]->getStoreLabel());
                $column['width']    =   '100px';
                
                // @TODO: temporary - need to enable Widget_Column class to reassign on the fly
                if($attrs[$code]->getFrontendInput() == 'input' || $attrs[$code]->getFrontendInput() == 'text')
                {
                    $column['renderer'] = 'Iksanika_Productupdater_Block_Widget_Grid_Column_Renderer_Input';
                }
                //
                
                // redefine editable type and renderers to static columns type
                if($attrs[$code]->getFrontendInput() == 'text')
                {
                    $column['type']    =   'input';
                }
                
                if ($attrs[$code]->getFrontendInput() == 'weight')
                {
                    $column['renderer'] =   'Iksanika_Productupdater_Block_Widget_Grid_Column_Renderer_Number';
                }
                
                if ($attrs[$code]->getFrontendInput() == 'price')
                {
                    $column['renderer']         =   'Iksanika_Productupdater_Block_Widget_Grid_Column_Renderer_Price';
                    $column['currency_code']    =   $currency;
                }
                
                if($attrs[$code]->getFrontendInput() == 'textarea')
                {
                    $column['type']     =   'iks_textarea';
                    $column['renderer'] =   'Iksanika_Productupdater_Block_Widget_Grid_Column_Renderer_Textarea';
                }
                
                if(($attrs[$code]->getFrontendInput() == 'date') )
//                        ||
//                  ($attrs[$code]->getFrontendInput() == 'datetime'))
                {
                    $column['renderer'] =   'Iksanika_Productupdater_Block_Widget_Grid_Column_Renderer_Datepicker';
                    $column['image']    =   Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN).'/adminhtml/default/default/images/grid-cal.gif';
//                    $column['format']   =   Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
                    $column['filter']   =   'adminhtml/widget_grid_column_filter_datetime';
//                    $column['filter']   =   'adminhtml/widget_grid_column_filter_date';
/*            case 'datetime':
                $filterClass = 'adminhtml/widget_grid_column_filter_datetime';
                break;
            case 'date':
                $filterClass = 'adminhtml/widget_grid_column_filter_date';
                break;
 */
                }
                
                if($attrs[$code]->getFrontendInput() == 'media_image')
                {
                    $column['width']    =   $imgWidth;
                    $column['renderer'] =   'Iksanika_Productupdater_Block_Widget_Grid_Column_Renderer_Image';
                }
                
                if ($attrs[$code]->getFrontendInput() == 'multiselect')
                {
                    $column['renderer'] = 'Iksanika_Productupdater_Block_Widget_Grid_Column_Renderer_Multiselect';
                    $column['filter']   = 'Iksanika_Productupdater_Block_Widget_Grid_Column_Filter_Multiselect';
                }
                
                if ($attrs[$code]->getFrontendInput() == 'select')
                {
                    $column['renderer'] =   'Iksanika_Productupdater_Block_Widget_Grid_Column_Renderer_Options';
                }
                
                $column['width'] = '100px';
                
                // load options for lists
                if (
                        $attrs[$code]->getFrontendInput() == 'select' || 
                        $attrs[$code]->getFrontendInput() == 'multiselect' || 
                        $attrs[$code]->getFrontendInput() == 'boolean')
                {
                    $attrOptions = array();
                    
                    if ($attrs[$code]->getAttributeCode() == 'custom_design')
                    {
                        $allOptions = $attrs[$code]->getSource()->getAllOptions();
                        if (is_array($allOptions) && !empty($allOptions))
                        {
                            foreach ($allOptions as $option)
                            {
                                if (!is_array($option['value']))
                                {
                                    if ($option['value'])
                                    {
                                        $attrOptions[$option['value']] = $option['value'];
                                    }
                                } else
                                {
                                    foreach ($option['value'] as $option2)
                                    {
                                        if (isset($option2['value']))
                                        {
                                            $attrOptions[$option2['value']] = $option2['value'];
                                        }
                                    }
                                }
                            }
                        }
                    } else
                    {
                        // getting attribute values with translation
                        $valuesCollection = Mage::getResourceModel('eav/entity_attribute_option_collection')
                            ->setAttributeFilter($attrs[$code]->getId())
                            ->setStoreFilter(Mage::helper('productupdater')->getStoreId(), false)
                            ->load();

                        if ($valuesCollection->getSize() > 0)
                        {
                            foreach ($valuesCollection as $item)
                            {
                                $attrOptions[$item->getId()] = $item->getValue();
                            }
                        } else
                        {
                            $selectOptions = $attrs[$code]->getFrontend()->getSelectOptions();
                            if ($selectOptions)
                            {
                                foreach ($selectOptions as $selectOption)
                                {
                                    $attrOptions[$selectOption['value']] = $selectOption['label'];
                                }
                            }
                        }
                    }

                    $column['type'] = 'options';
                    $column['options'] = $attrOptions;
                    $column['renderer'] =   'Iksanika_Productupdater_Block_Widget_Grid_Column_Renderer_Options';
                }

                if($attrs[$code])
                {
                    $column['attribute'] = $attrs[$code];
                }


                if ($attrs[$code]->getAttributeCode() == 'tier_price')
                {
                    $column['renderer'] = 'Iksanika_Productupdater_Block_Widget_Grid_Column_Renderer_TierPrice';
                    $column['sortable'] = false;
                }

                if ($attrs[$code]->getAttributeCode() == 'group_price')
                {
                    $column['renderer'] = 'Iksanika_Productupdater_Block_Widget_Grid_Column_Renderer_GroupPrice';
                    $column['sortable'] = false;
                }
            }else
            {
                $column['index']    =   $code;
                $column['header']   =   Mage::helper('catalog')->__($code);
                $column['width']    =   '100px';
                $column['renderer'] =   'Iksanika_Productupdater_Block_Widget_Grid_Column_Renderer_Input';
            }
            // add column to grid
            $this->addColumn($code, $column);
        }
        
        
        $this->addColumn('action',
            array(
                'header'    => Mage::helper('catalog')->__('Action'),
//                'width'     => '50px',
                'type'      => 'action',
                'getter'    => 'getId',
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'link_action',
                'actions'   => array(
                    array(
                        'caption' => Mage::helper('catalog')->__('Edit'),
                        'id' => "editlink",
                        'url' => array(
                            'base' => 'adminhtml/catalog_product/edit',
                            'params' => array('store' => $this->getRequest()->getParam('store'))
                        ),
                        'field'   => 'id'
                    ),
                ),
        ));

        
        $this->addColumn('view', array(
            'header' => Mage::helper('catalog')->__('View'),
//            'width' => '40px',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('catalog')->__('View'),
                    'url' => array(
                        'base' => 'catalog/product/view',
                        'params' => array('store' => $this->getRequest()->getParam('store'))
                    ),
                    'field' => 'id'
                )
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'link_view',
        ));









//        $this->addRssList('rss/catalog/notifystock', Mage::helper('catalog')->__('Notify Low Stock RSS'));
        if (Mage::helper('catalog')->isModuleEnabled('Mage_Rss')) 
        {
            $this->addRssList('rss/catalog/notifystock', Mage::helper('catalog')->__('Notify Low Stock RSS'));
        }
        
        $this->addExportType('adminhtml/productupdater/exportCsv', Mage::helper('customer')->__('CSV'));
        $this->addExportType('adminhtml/productupdater/exportXml', Mage::helper('customer')->__('XML'));

        $this->setDestElementId('edit_form');
        
//        return parent::_prepareColumns();
        parent::_prepareColumns();
        
        $this->rearangeColumnsPositions();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('product');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'=> Mage::helper('catalog')->__('Delete'),
            'url'  => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('catalog')->__('Are you sure?'),
            
            'css'       =>  'iks-board-red',
            'notice'    =>  Mage::helper('productupdater')->__('Remove selected products.'),
        ));

        $statuses = Mage::getSingleton('catalog/product_status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('catalog')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current' => true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('catalog')->__('Status'),
                         'values' => $statuses
                     )
             ),
            'notice'    =>  Mage::helper('productupdater')->__('Change selected products status.'),
            'uititle'  =>  Mage::helper('productupdater')->__('Change Status'),
            'uinotice' =>  Mage::helper('productupdater')->__('Change selected products status.'),
        ));
        
        $this->getMassactionBlock()->addItem('attributes', 
            array(
                'label' => Mage::helper('catalog')->__('Update attributes'),
                'url'   => $this->getUrl('adminhtml/catalog_product_action_attribute/edit', array('_current'=>true)),
                'notice' => Mage::helper('productupdater')->__('Update products attributes values in builk.'),
                )
        );
        
//        $this->getMassactionBlock()->addItem('otherDivider', $this->getSubDivider("------Additional------"));
        
        $this->getMassactionBlock()->addItem('refreshProducts', 
            array(
                'label' => $this->__('Refresh Products'),
                'url'   => $this->getUrl('*/*/massRefreshProducts', array('_current'=>true))
            )
        );
        
        /*
         * Prepare list of column for update
         */
        $fields = self::getColumnForUpdate();
        
        $this->getMassactionBlock()->addItem('save', 
            array(
                'label' => Mage::helper('catalog')->__('Update'),
                'url'   => $this->getUrl('*/*/massUpdateProducts', array('_current'=>true, '_query' => '')),
                'fields' => $fields
            )
        );
        
        
        $this->getMassactionBlock()->addItem('duplicate', 
            array(
                'label'     =>  Mage::helper('catalog')->__('Duplicate'),
                'url'       =>  $this->getUrl('*/*/duplicateProducts', array('_current'=>true)),
                
                'css'       =>  'iks-clone',
                'notice'    =>  Mage::helper('productupdater')->__('Make a copy of selected products.'),
            )
        );
        
        
        $this->getMassactionBlock()->addItem('attributeSet', 
            array(
                'label'         =>  Mage::helper('catalog')->__('Change Attribute Set'),
                'url'           =>  $this->getUrl('*/*/changeAttributeSetProducts', array('_current'=>true)),
                'additional'    =>  $this->getAttributeSets($this->__('To: ')),
                'notice' => Mage::helper('productupdater')->__('Change products attributes set to new selected one.'),
                'uititle'  =>  Mage::helper('productupdater')->__('Change Attribute Set'),
                'uinotice' =>  Mage::helper('productupdater')->__('Change products attributes set to new selected one.'),
            )
        );
        
//        $this->getMassactionBlock()->addItem('categoryActionDivider', $this->getCleanDivider());
//        $this->getMassactionBlock()->addItem('otherCategoryActionDivider', $this->getDivider("Category"));

        $this->getMassactionBlock()->addItem('addCategory', 
            array(
                'label'     =>  Mage::helper('catalog')->__('Category: Add'),
                'url'       =>  $this->getUrl('*/*/addCategory', array('_current'=>true)),
                'additional'=>  $this->getCategoriesTree($this->__('Category IDs ')),
                'css'       =>  'iks-category',
                'notice'    =>  Mage::helper('productupdater')->__('Assign selected products to selected categories.'),
                'uititle'  =>  Mage::helper('productupdater')->__('Category: Add'),
                'uinotice' =>  Mage::helper('productupdater')->__('Assign selected products to selected categories.'),
            )
        );

        $this->getMassactionBlock()->addItem('removeCategory', 
            array(
                'label'     =>  Mage::helper('catalog')->__('Category: Remove'),
                'url'       =>  $this->getUrl('*/*/removeCategory', array('_current'=>true)),
                'additional'=>  $this->getCategoriesTree($this->__('Category IDs ')),
                'css'       =>  'iks-category',
                'notice'    =>  Mage::helper('productupdater')->__('Unassign selected products from selected categories.'),
                'uititle'  =>  Mage::helper('productupdater')->__('Category: Remove'),
                'uinotice' =>  Mage::helper('productupdater')->__('Unassign selected products from selected categories.'),
            )
        );

        $this->getMassactionBlock()->addItem('replaceCategory', 
            array(
                'label'     =>  Mage::helper('catalog')->__('Category: Replace'),
                'url'       =>  $this->getUrl('*/*/replaceCategory', array('_current'=>true)),
                'additional'=>  $this->getCategoriesTree($this->__('Category IDs ')),
                'css'       =>  'iks-category',
                'notice'    =>  Mage::helper('productupdater')->__('Unassign products from all categories and assign this products to specified list of cateogires.'),
            )
        );
        
        
//        $this->getMassactionBlock()->addItem('stockActionDivider', $this->getCleanDivider());
//        $this->getMassactionBlock()->addItem('otherStockActionDivider', $this->getDivider("Stock Inventory"));

        $this->getMassactionBlock()->addItem('updateQty', 
            array(
                'label'     =>  Mage::helper('productupdater')->__('Update: Qty'),
                'url'       =>  $this->getUrl('*/*/updateQty', array('_current'=>true)),
                'additional'=>  $this->getProductField($this->__('Qty: '), 'qty'),
                'notice'    =>  Mage::helper('productupdater')->__('Bulk \'Qty\' update by absolute and relative values.'),
                'uititle'  =>  Mage::helper('productupdater')->__('Update: Qty'),
                'uinotice' =>  Mage::helper('productupdater')->__('Bulk \\\'Qty\\\' update by absolute and relative values.'),
            )
        );

        $statuses = Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray();
        array_unshift($statuses, array('label'=>'', 'value'=>''));
        
        $this->getMassactionBlock()->addItem('updateQtyStatus', array(
             'label'=> Mage::helper('productupdater')->__('Update: Is in Stock'),
             'url'  => $this->getUrl('*/*/updateQtyStatus', array('_current' => true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'is_in_stock',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('productupdater')->__('Is in Stock: '),
                         'values' => $statuses
                     )
             ),
            'notice'    =>  Mage::helper('productupdater')->__('Bulk \'Is in Stock\' attribute update.'),
            'uititle'  =>  Mage::helper('productupdater')->__('Update: Is in Stock'),
            'uinotice' =>  Mage::helper('productupdater')->__('Bulk \\\'Is in Stock\\\' attribute update.'),
        ));
        
        
//        $this->getMassactionBlock()->addItem('pricesActionDivider', $this->getCleanDivider());
//        $this->getMassactionBlock()->addItem('otherPricesActionDivider', $this->getDivider("Prices"));

        $this->getMassactionBlock()->addItem('updatePrice', 
            array(
                'label'     =>  Mage::helper('catalog')->__('Update: Price'),
                'url'       =>  $this->getUrl('*/*/updatePrice', array('_current'=>true)),
                'additional'=>  $this->getPriceField($this->__('By: ')),
                'notice'    =>  Mage::helper('productupdater')->__('Bulk \'Price\' update by absolute and relative values.'),
                'uititle'  =>  Mage::helper('productupdater')->__('Update: Price'),
                'uinotice' =>  Mage::helper('productupdater')->__('Bulk \\\'Price\\\' update by absolute and relative values.'),
            )
        );

        $this->getMassactionBlock()->addItem('updateCost', 
            array(
                'label'     =>  Mage::helper('catalog')->__('Update: Cost'),
                'url'       =>  $this->getUrl('*/*/updateCost', array('_current'=>true)),
                'additional'=>  $this->getPriceField($this->__('By: '), 'cost'),
                'notice'    =>  Mage::helper('productupdater')->__('Bulk \'Cost\' update by absolute and relative values.'),
                'uititle'  =>  Mage::helper('productupdater')->__('Update: Cost'),
                'uinotice' =>  Mage::helper('productupdater')->__('Bulk \\\'Cost\\\' update by absolute and relative values.'),
            )
        );

        $this->getMassactionBlock()->addItem('updateSpecialPrice', 
            array(
                'label'     =>  Mage::helper('catalog')->__('Update: Special Price'),
                'url'       =>  $this->getUrl('*/*/updateSpecialPrice', array('_current'=>true)),
                'additional'=>  $this->getPriceField($this->__('By: '), 'special_price'),
                'notice'    =>  Mage::helper('productupdater')->__('Bulk \'Special Price\' update by absolute and relative values.'),
                'uititle'  =>  Mage::helper('productupdater')->__('Update: Special Price'),
                'uinotice' =>  Mage::helper('productupdater')->__('Bulk \\\'Special Price\\\' update by absolute and relative values.'),
            )
        );

        $this->getMassactionBlock()->addItem('updatePriceByCost', 
            array(
                'label'     =>  Mage::helper('catalog')->__('Update: Price based on Cost'),
                'url'       =>  $this->getUrl('*/*/updatePriceByCost', array('_current'=>true)),
                'additional'=>  $this->getPriceField($this->__('By: ')),
                'notice'    =>  Mage::helper('productupdater')->__('Bulk \'Price\' update by absolute and relative values based on \'Cost\' values.'),
                'uititle'  =>  Mage::helper('productupdater')->__('Update: Price based on Cost'),
                'uinotice' =>  Mage::helper('productupdater')->__('Bulk \\\'Price\\\' update by absolute and relative values based on \\\'Cost\\\' values.'),
            )
        );

        $this->getMassactionBlock()->addItem('updateSpecialPriceByCost', 
            array(
                'label'     =>  Mage::helper('catalog')->__('Update: Special Price based on Cost'),
                'url'       =>  $this->getUrl('*/*/updateSpecialPriceByCost', array('_current'=>true)),
                'additional'=>  $this->getPriceField($this->__('By: ')),
                'notice'    =>  Mage::helper('productupdater')->__('Bulk \'Special Price\' update by absolute and relative values based on \'Cost\' values.'),
                'uititle'  =>  Mage::helper('productupdater')->__('Update: Special Price based on Cost'),
                'uinotice' =>  Mage::helper('productupdater')->__('Bulk \\\'Special Price\\\' update by absolute and relative values based on \\\'Cost\\\' values.'),
            )
        );

        $this->getMassactionBlock()->addItem('updateSpecialPriceByPrice', 
            array(
                'label'     =>  Mage::helper('catalog')->__('Update: Special Price based on Price'),
                'url'       =>  $this->getUrl('*/*/updateSpecialPriceByPrice', array('_current'=>true)),
                'additional'=>  $this->getPriceField($this->__('By: ')),
                'notice'    =>  Mage::helper('productupdater')->__('Bulk \'Special Price\' update by absolute and relative values based on \'Price\' values.'),
                'uititle'  =>  Mage::helper('productupdater')->__('Update: Special Price based on Price'),
                'uinotice' =>  Mage::helper('productupdater')->__('Bulk \\\'Special Price\\\' update by absolute and relative values based on \\\'Price\\\' values.'),
            )
        );
        
        
        
        if($this->isAllowed['related'] || $this->isAllowed['cross_sell'] || $this->isAllowed['up_sell'])
        {
//            $this->getMassactionBlock()->addItem('relatedDivider', $this->getCleanDivider());

//            $this->getMassactionBlock()->addItem('otherDividerSalesMotivation', $this->getDivider("Product Relator"));
        }
        
        if($this->isAllowed['related']) 
        {
            $this->getMassactionBlock()->addItem('relatedEachOther', array(
                'label' => $this->__('Related: To Each Other'),
                'url'   => $this->getUrl('*/*/massRelatedEachOther', array('_current'=>true)),
                'callback' => 'specifyRelatedEachOther()',
                'css'       =>  'iks-relate',
                'notice'    =>  Mage::helper('productupdater')->__('Relate selected products to each other.'),
            ));
            $this->getMassactionBlock()->addItem('relatedTo', array(
                'label' => $this->__('Related: Add ..'),
                'url'   => $this->getUrl('*/*/massRelatedTo', array('_current'=>true)),
                'callback' => 'specifyRelatedProducts()',
                'css'       =>  'iks-relate',
                'notice'    =>  Mage::helper('productupdater')->__('Relate specified products to selected list of products.'),
            ));
            $this->getMassactionBlock()->addItem('relatedClean', array(
                'label' => $this->__('Related: Clear'),
                'url'   => $this->getUrl('*/*/massRelatedClean', array('_current'=>true)),
                'callback' => 'specifyRelatedClean()',
                'css'       =>  'iks-relate',
                'notice'    =>  Mage::helper('productupdater')->__('Remove all related products from selected list of products.'),
            ));
        }
        
        
        if($this->isAllowed['cross_sell']) 
        {
//            $this->getMassactionBlock()->addItem('crossSellDivider', $this->getCleanDivider());

            $this->getMassactionBlock()->addItem('crossSellEachOther', array(
                'label' => $this->__('Cross-Sell: To Each Other'),
                'url'   => $this->getUrl('*/*/massCrossSellEachOther', array('_current'=>true)),
                'callback' => 'specifyCrossSellEachOther()',
                'css'       =>  'iks-relate',
                'notice'    =>  Mage::helper('productupdater')->__('Cross-sell selected products to each other.'),
            ));
            $this->getMassactionBlock()->addItem('crossSellTo', array(
                'label' => $this->__('Cross-Sell: Add ..'),
                'url'   => $this->getUrl('*/*/massCrossSellTo', array('_current'=>true)),
                'callback' => 'chooseWhatToCrossSellTo()',
                'css'       =>  'iks-relate',
                'notice'    =>  Mage::helper('productupdater')->__('Cross-sell specified products to selected list of products.'),
            ));
            $this->getMassactionBlock()->addItem('crossSellClear', array(
                'label' => $this->__('Cross-Sell: Clear'),
                'url'   => $this->getUrl('*/*/massCrossSellClear', array('_current'=>true)),
                'callback' => 'specifyCrossSellClean()',
                'css'       =>  'iks-relate',
                'notice'    =>  Mage::helper('productupdater')->__('Remove all cross-sells products from selected list of products.'),
            ));
        }
        
        
        if($this->isAllowed['up_sell']) 
        {
//            $this->getMassactionBlock()->addItem('upSellDivider', $this->getCleanDivider());
            
            $this->getMassactionBlock()->addItem('upSellTo', array(
                'label' => $this->__('Up-Sells: Add ..'),
                'url'   => $this->getUrl('*/*/massUpSellTo', array('_current'=>true)),
                'callback' => 'chooseWhatToUpSellTo()',
                'css'       =>  'iks-relate',
                'notice'    =>  Mage::helper('productupdater')->__('Up-sell specified products to selected list of products.'),
            ));
            $this->getMassactionBlock()->addItem('upSellClear', array(
                'label' => $this->__('Up-Sells: Clear'),
                'url'   => $this->getUrl('*/*/massUpSellClear', array('_current'=>true)),
                'callback' => 'specifyUpSellClean()',
                'css'       =>  'iks-relate',
                'notice'    =>  Mage::helper('productupdater')->__('Remove all up-sell products from selected list of products.'),
            ));
        }
        
        // @TODO: enable it and improve for use in next extension versions
//        $this->getMassactionBlock()->addItem('otherDivider', $this->getCleanDivider());
//        $this->getMassactionBlock()->addItem('otherDividerOther', $this->getDivider("Other"));
//
//        $this->getMassactionBlock()->addItem('copyAttributes',
//            array(
//                'label'     =>  Mage::helper('catalog')->__('Copy: Attributes'),
//                'url'       =>  $this->getUrl('*/*/copyAttributes', array('_current'=>true)),
//                'additional'=>  $this->getPriceField($this->__('Based on Product: ')),
//            )
//        );
        
        return $this;
    }
    
    
    public function getRowUrl($row)
    {
        return $this->getUrl('adminhtml/catalog_product/edit', array(
            'store'=>$this->getRequest()->getParam('store'),
            'id'=>$row->getId())
        );
    }
    
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
    
    protected function getDivider($divider="*******") {
        $dividerTemplate = array(
          'label' => '********'.$this->__($divider).'********',
          'url'   => $this->getUrl('*/*/index', array('_current'=>true)),
          'callback' => "null"
        );
        return $dividerTemplate;
    }

    protected function getSubDivider($divider="-------") {
        $dividerTemplate = array(
          'label' => '--------'.$this->__($divider).'--------',
          'url'   => $this->getUrl('*/*/index', array('_current'=>true)),
          'callback' => "null"
        );
        return $dividerTemplate;
    }

    protected function getCleanDivider() {
        $dividerTemplate = array(
          'label' => ' ',
          'url'   => $this->getUrl('*/*/index', array('_current'=>true)),
          'callback' => "null"
        );
        return $dividerTemplate;
    }
    
    public function getCsv()
    {
        $csv = '';
        $this->_isExport = true;
        $this->_prepareGrid();
        $this->getCollection()->getSelect()->limit();
        $this->getCollection()->setPageSize(0);
        $this->getCollection()->load();
        $this->_afterLoadCollection();
        $data = array();
        foreach ($this->_columns as $column) {
            if (!$column->getIsSystem()) {
                $data[] = '"'.$column->getExportHeader().'"';
            }
        }
        $csv.= implode(',', $data)."\n";

        
        foreach ($this->getCollection() as $item) {
            $data = array();
            foreach ($this->_columns as $column) {
                if(!$column->getIsSystem())
                {
                    $colIndex = $column->getIndex();
                    $colContent = $item->$colIndex;
                    if($colIndex == 'category_ids')
                        $colContent = implode(',', $item->getCategoryIds());
                    if($colIndex == 'related_ids')
                        $colContent = implode(',', $item->getRelatedProductIds());
                    if($colIndex == 'cross_sell_ids')
                        $colContent = implode(',', $item->getCrossSellProductIds());
                    if($colIndex == 'up_sell_ids')
                        $colContent = implode(',', $item->getUpSellProductIds());
                    if($colIndex == 'associated_groupped_ids')
                    {
                        $ids = array();
                        if($item->getTypeId() == 'grouped')
                        {
                            $childrenList = $item->getTypeInstance(true)->getAssociatedProducts($item);
                            foreach($childrenList as $childProduct)
                                $ids[] = $childProduct->getId();
                        }
                        $colContent = implode(',', $ids);
                    }
                    if($colIndex == 'associated_configurable_ids')
                    {
                        $ids = array();
                        if($item->getTypeId() == 'configurable')
                            $ids = $item->getTypeInstance()->getUsedProductIds();
                        $colContent = implode(',', $ids);
                    }
                    
                    $data[] = '"'.str_replace(array('"', '\\'), array('""', '\\\\'), $colContent).'"';
                }
            }
            $csv.= implode(',', $data)."\n";
        }

        if ($this->getCountTotals())
        {
            $data = array();
            foreach ($this->_columns as $column) {
                if (!$column->getIsSystem()) {
                    $data[] = '"' . str_replace(array('"', '\\'), array('""', '\\\\'),
                        $column->getRowFieldExport($this->getTotals())) . '"';
                }
            }
            $csv.= implode(',', $data)."\n";
        }

        return $csv;
    }

    
    

    
    

    public function rearangeColumnsPositions() 
    {
        $keys           =   array_keys($this->_columns);
        $values         =   array_values($this->_columns);
        $orderedFields  =   (string) Mage::getStoreConfig('productupdater/attributes/positions' . Mage::getSingleton('admin/session')->getUser()->getId());
/* required for new version (profiling version)       
        $currentProfile = Mage::helper('productupdater/profile')->getCurrentProfile();
//        $currentProfile->columns_positions = implode(',', $orderedFields);
        $orderedFields = $currentProfile->columns_positions;
*/        
        if (!$orderedFields) return $this;
        
        $orderedFields = explode(',', $orderedFields);

        for ($i = 0; $i < count($orderedFields) - 1; $i++) 
        {
            $columnsOrder[$orderedFields[$i + 1]] = $orderedFields[$i];
        }

        foreach ($columnsOrder as $columnId => $after) 
        {
            if (array_search($after, $keys) !== false) 
            {
                $curPosition        =   array_search($columnId, $keys);

                $key                =   array_splice($keys, $curPosition, 1);
                $value              =   array_splice($values, $curPosition, 1);

                $tarPosition     =   array_search($after, $keys) + 1;

                array_splice($keys, $tarPosition, 0, $key);
                array_splice($values, $tarPosition, 0, $value);

                $this->_columns = array_combine($keys, $values);
            }
        }

        end($this->_columns);
        $this->_lastColumnId = key($this->_columns);
        return $this;
    }
    
    
    
    
    
    
    
    protected function getCategoriesTree($title)
    {
        $element = array('category_value' => array(
            'name'  =>  'category',
            'type'  =>  'text',
            'class' =>  'required-entry',
            'label' =>  $title,
        ));
        
        if (Mage::getStoreConfig('productupdater/massactions/categorynames', Mage::helper('productupdater')->getStoreId()))
        { 
            $rootId = Mage::app()->getStore(Mage::helper('productupdater')->getStoreId())->getRootCategoryId();
            $element['category_value']['label']   =   Mage::helper('productupdater')->__('Category');
            $element['category_value']['type']    =   'select';
            $element['category_value']['values']  =   Mage::helper('productupdater/category')->getTree($rootId);
        } 
        
        return $element;      
    } 
    
    protected function getAttributeSets($title)
    {
        $element = array('attribute_set_value' => array(
            'name'  =>  'attribute_set',
            'type'  =>  'select',
            'class' =>  'required-entry',
            'label' =>  Mage::helper('productupdater')->__($title),
            'values'=>  Mage::getResourceModel('eav/entity_attribute_set_collection')
                ->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId())
                ->load()->toOptionHash(),
        ));
        
        return $element;      
    } 
    
    protected function getPriceField($title, $field = 'price')
    {
        $element = array('price_value' => array(
            'name'  =>  'price',
            'type'  =>  'text',
            'class' =>  ($field != 'cost' && $field != 'special_price') ? 'required-entry' : '',
            'label' =>  $title,
        ));
        
        return $element;      
    } 
    
    protected function getProductField($title, $field = 'product')
    {
        $element = array('product_value' => array(
            'name'  =>  $field,
            'type'  =>  'text',
            'class' =>  'required-entry',
            'label' =>  $title,
        ));
        
        return $element;      
    } 
    
    
    
    
    
    
    
    
}