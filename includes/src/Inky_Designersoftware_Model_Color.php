<?php

class Inky_Designersoftware_Model_Color extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('designersoftware/color');
    }
    
    public function getColorCollection(){		
		$collection = Mage::getModel('designersoftware/color')->getCollection();		
		return $collection;		
	}
	
	public function getColorCollectionById($id){
		$collection = $this->getColorCollection();
		
		$collection = $collection->addFieldToFilter('color_id',$id)						
						->getFirstItem();	
							
		return $collection;		
	}	
	
    public function getCollectionByClipart(){
		$collection = $this->getColorCollection()
							->addFieldToFilter('clip_status',1);
		
		return $collection;							
	}	
	
	public function getColorcodeById($id){
		$collection = $this->getColorCollection()
							->addFieldTofilter('color_id', $id)
							->addFieldTofilter('status',1)
							->getFirstItem();
							
		return $collection->getColorcode();
							
	}
    
    public function getCollectionByText(){
		$collection = $this->getColorCollection()
						->addFieldToFilter('text_status',1);
		
		return $collection;
	}
    
    public function isClipEnable($id)
    {
        $check = $this->getColorCollection()
					->addFieldToFilter('color_id', $id)
					->addFieldToFilter('clip_status',1)
					->getFirstItem()->getData();
		
		
		if(count($check)>0)		
			return true;		
		else
			return false;
    }
    
    public function isTextEnable($id)
    {
        $check = $this->getColorCollection()
					->addFieldToFilter('color_id', $id)
					->addFieldToFilter('text_status',1)
					->getFirstItem()
					->getData();
		
		if(count($check)>0)
			return true;
		else
			return false;			
    } 
}
