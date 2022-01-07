<?php

class Inky_Designersoftware_Block_Adminhtml_Parts_Layers_Edit_Tab_Texture_Grid extends Mage_Adminhtml_Block_Widget_Grid 		
{
	public function _construct()
	{	
		parent::_construct();		
		$this->setId('parts_layers_textureGrid');
		$this->setDefaultSort('texture_id');
		$this->setDefaultFilter(array('in_products'=>1)); // By default we have added a filter for the rows, that in_products value to be 1
		$this->setDefaultDir('ASC');
		//enable ajax grid
		$this->setUseAjax(true);
	    $this->setSaveParametersInSession(true);
	}	
	
	protected function _prepareCollection()
    {
        $collection = Mage::getModel('designersoftware/texture')->getCollection()->addFieldToFilter('status',1);        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    protected function _addColumnFilterToCollection($column)
    {		
        // Set custom filter for in product flag
        if ($column->getId() == 'in_products') {
            $textureIds = $this->_getSelectedTextures();
            if (empty($textureIds)) {
                $textureIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('texture_id', array('in'=>$textureIds));
            } else {
                if($textureIds) {
                    $this->getCollection()->addFieldToFilter('texture_id', array('nin'=>$textureIds));
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
					'values'            => $this->_getSelectedTextures(),
					'align'             => 'center',
					'index'             => 'texture_id'               
		));		
	  
		
	  $this->addColumn('texture_id', array(
		  'header'    => Mage::helper('designersoftware')->__('ID'),
		  'align'     =>'right',
		  'width'     => '50px',
		  'index'     => 'texture_id',
	  ));
	  
	  $this->addColumn('filename', array(
          'header'    => Mage::helper('designersoftware')->__('Image'),
          'align'     =>'left',
          'index'     => 'filename',
          'filter'		=>false,
          'sortable'	=>false,
          'width'		=>50,
          'renderer'	=> 'Inky_Designersoftware_Block_Adminhtml_Texture_Renderer_Image',
      ));

	  $this->addColumn('title_texture', array(
		  'header'    => Mage::helper('designersoftware')->__('Title'),
		  'align'     =>'left',
		  'index'     => 'title',		  	  
	  ));
	  
	 $this->addColumn('texture_code', array(
		  'header'    => Mage::helper('designersoftware')->__('Texture Code'),
		  'align'     =>'left',
		  'index'     => 'texture_code',		 
	  ));	
	  
	  $this->addColumn('position', array( 
			'header' => Mage::helper('designersoftware')->__('Sort Order'), 
			'name' => 'position', 'width' => 60, 
			'type' => 'number', 
			'validate_class' => 'validate-number', 
			'index' => 'position', 
			'editable' => true, 
			'edit_only' => true 
	)); 
	  
	  return parent::_prepareColumns();
	}  
    
    protected function _getSelectedTextures()   // Used in grid to return selected customers values.
    {
		
        $textures = array_keys($this->getSelectedTextures());        
        return $textures;
    }
 
    public function getSelectedTextures()
    {
        // Color Data
        $tm_id = $this->getRequest()->getParam('id');
        if($tm_id >0 ) {			
            $partsLayersCollection = Mage::getModel('designersoftware/parts_layers')->getCollectionById($tm_id);											
			$texture = unserialize($partsLayersCollection->getTextureIds());   					
        }  	
		
		$textureIds = array();
		foreach($texture as $col) {
			$textureIds[$col] = array('position'=>$col);
		}
        
        return $textureIds;
    }
    
    public function getGridUrl()
    {
        return $this->_getData('grid_url') ? $this->_getData('grid_url') : $this->getUrl('*/*/texturegrid', array('_current'=>true));
    }     
}
