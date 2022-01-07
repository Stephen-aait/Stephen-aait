<?php

class Inky_Designersoftware_Model_Sizes extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('designersoftware/sizes');
    }
    
    public function getSizesCollection(){
		$collection =  Mage::getModel('designersoftware/sizes')->getCollection()
									->addFieldToFilter('status',1);
		
		
		return $collection;
	}
	
	public function getColorCollectionById($sizesId){
		$collection = $this->getSizesCollection();
		
		$collection = $collection->addFieldToFilter('sizes_id',$sizesId)						
						->getFirstItem();	
							
		return unserialize($collection->getColorIds());
	}
	
	public function getColorPrice($sizesId, $colorId){
		$collection = $this->getSizesCollection();
		
		if(!empty($sizesId)):
			$collection = $collection->addFieldToFilter('sizes_id',$sizesId)->getFirstItem();
			$colorPrice = $collection->getData('color_price');	
				
			if(empty($colorPrice)):	
				$defaultColorPrice = $collection->getData('default_color_price');	
							
				if($defaultColorPrice>0):
					return $defaultColorPrice;
				else:	
					return 0;
				endif;
			else:
				$color_price = unserialize($collection->getData('color_price'));						
				if(!empty($color_price[$colorId]) && $color_price[$colorId]>0):
					return $color_price[$colorId];
				else:
					$defaultColorPrice = $collection->getData('default_color_price');
					
					if($defaultColorPrice>0):
						return $defaultColorPrice;
					else:	
						return 0;
					endif;	
				endif;
			endif;
		else:
			return false;		
		endif;	
		
	}
}
