<?php
class A2bizz_Customerdesign_Block_Adminhtml_Customer_Renderer_Drawarea extends Mage_Adminhtml_Block_Customer_Edit_Tab_View_Grid_Renderer_Item 
{
	
	protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('a2bizz/customer/edit/tab/view/grid/item.phtml');
        
        return $this;
    }
    
    /*
     * Returns product associated with this block
     *
     * @param Mage_Catalog_Model_Product $product
     * @return string
     */
    public function getProduct()
    {
        return $this->getItem()->getProduct();
    }
    
    public function render(Varien_Object $item) {
		$this->setItem($item);
        return $this->toHtml();		
    }
    
    public function getDesignerProductParams($designId){
						
		if($designId>0):
								
			$params='';			
			$params .='did='.$designId;
			$params .='&';
			$params .='mode=edit'; 
			//$params .='&';
			//$params .='code='.$designCode;
			
			$params = base64_encode($params);			
			return $params;
		endif;
	}
}
?>
