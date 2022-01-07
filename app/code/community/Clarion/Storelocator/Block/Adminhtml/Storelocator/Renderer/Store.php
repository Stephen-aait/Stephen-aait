<?php
/**
 * Store renderer block
 * 
 * @category    Clarion
 * @package     Clarion_Storelocator
 * @author      Clarion Magento Team <magento@clariontechnologies.co.in>
 * 
 */
class Clarion_Storelocator_Block_Adminhtml_Storelocator_Renderer_Store extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * Renders store column
     *
     * @param   Varien_Object $row
     * @return  string
     */
    public function render(Varien_Object $row)
    {
        $storeCode = array();
        
        if($row->getData($this->getColumn()->getIndex())==""){
            return "";
        }
        else{
            $storeIds =  $row->getData($this->getColumn()->getIndex());
            
            if(!is_array($storeIds)){
                $storeIds = array($storeIds);
            }
            
            if(!empty($storeIds)){
                foreach($storeIds as $storeId){
                    $storeCode[] = Mage::getModel('core/store')->load($storeId)->getCode();
                }
            }
            
            return implode(",", $storeCode);
        }
    }
}