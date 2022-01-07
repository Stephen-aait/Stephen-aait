<?php

class Inky_Designersoftware_Block_Adminhtml_Parts_Layers_Edit_Tab_Color_Grid extends Mage_Adminhtml_Block_Widget_Grid 		
{
	public function _construct()
	{	
		parent::_construct();		
		$this->setId('parts_layers_colorGrid');
		$this->setDefaultSort('color_id');
		$this->setDefaultFilter(array('in_products'=>1)); // By default we have added a filter for the rows, that in_products value to be 1
		$this->setDefaultDir('ASC');
		$this->setDefaultLimit(200);
		
		//enable ajax grid
		$this->setUseAjax(true);
	    $this->setSaveParametersInSession(true);
	}	
	
	protected function _prepareCollection()
    {
        $collection = Mage::getModel('designersoftware/color')->getCollection()->addFieldToFilter('status',1);        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    protected function _addColumnFilterToCollection($column)
    {		
        // Set custom filter for in product flag
        if ($column->getId() == 'in_products') {
            $colorIds = $this->_getSelectedColors();
            if (empty($colorIds)) {
                $colorIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('color_id', array('in'=>$colorIds));
            } else {
                if($colorIds) {
                    $this->getCollection()->addFieldToFilter('color_id', array('nin'=>$colorIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

	protected function _prepareColumns()
	{
	
	  $this->addColumn('in_products', array(
		'header_css_class'  => 'a-center',
		'type'              => 'checkbox',
		'name'              => 'in_products',
		'values'            => $this->_getSelectedColors(),
		'align'             => 'center',
		'index'             => 'color_id'               
	  ));		
	  
		
	  $this->addColumn('color_id', array(
		  'header'    		=> Mage::helper('designersoftware')->__('ID'),
		  'align'     		=>'right',
		  'width'     		=> '50px',
		  'index'     		=> 'color_id',
	  ));

	  $this->addColumn('title_color', array(
		  'header'    		=> Mage::helper('designersoftware')->__('Color Title'),
		  'align'     		=>'left',
		  'index'     		=> 'title',
		  'name'	  		=> 'title'			  
	  ));
	  
	 $this->addColumn('colorcode', array(
		  'header'    		=> Mage::helper('designersoftware')->__('Color Code'),
		  'align'     		=>'left',
		  'index'     		=> 'colorcode',
		  'renderer'  		=>'Inky_Designersoftware_Block_Adminhtml_Color_Renderer_Color',
	  ));	
	  
	  $this->addColumn('color_price', array(
		 'header'    		=> Mage::helper('designersoftware')->__('Color Price'),
		  'align'     		=> 'left',
		  'index'     		=> 'colorcode',
		  'width' 			=> 60, 
		  'renderer'  		=> 'Inky_Designersoftware_Block_Adminhtml_Color_Renderer_Price',		  
	  ));
	  
	  $this->addColumn('position', array( 
			'header' 		=> Mage::helper('designersoftware')->__('Sort Order'), 
			'name' 			=> 'position', 
			'width' 		=> 60, 
			'type' 			=> 'number', 
			'validate_class'=> 'validate-number', 
			'index' 		=> 'position', 
			'editable' 		=> true, 
			'edit_only' 	=> true 
	  ));
	  
	  return parent::_prepareColumns();
	}  
    
    protected function _getSelectedColors()   // Used in grid to return selected customers values.
    {
		$colors = array_keys($this->getSelectedColors());                
        return $colors;
    }
 
    public function getSelectedColors()
    {
        // Color Data
        $tm_id = $this->getRequest()->getParam('id');
        if($tm_id >0 ) {			
            $partsLayersCollection = Mage::getModel('designersoftware/parts_layers')->getCollectionById($tm_id);											           
			$color = unserialize($partsLayersCollection->getColorIds());   					
        }  	
		
		$colorIds = array();		
		foreach($color as $col) {
			$colorIds[$col] = array('position'=>$col);
		}
        
        return $colorIds;
    }
    
    public function getGridUrl()
    {
        return $this->_getData('grid_url') ? $this->_getData('grid_url') : $this->getUrl('*/*/colorgrid', array('_current'=>true));
    }     
}
