<?php
class Inky_Designersoftware_Block_Customer_Design extends Mage_Wishlist_Block_Customer_Wishlist
{
	protected $_collection;
	
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    } 
    
    
    public function getTitle(){
		return $this->__('My Designs');
	}   
	
	/**
     * Retrieve Back URL
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('customer/account/');
    }
    
    public function hasDesign(){
				
		$count = Mage::getModel('designersoftware/designersoftware')->getCustomerDesignCount();
		if($count > 0):
			return true;		
		else:
			return false;
		endif;
		
	}
		
	public function getDesigns()
    {
        if (is_null($this->_collection)) {
            $this->_collection = Mage::getModel('designersoftware/designersoftware')->getCustomerDesignCollection();            
        }

        return $this->_collection;
    }
}
