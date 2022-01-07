<?php
class Inky_Designersoftware_Block_Adminhtml_Designersoftware_Renderer_Action extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $data = $row->getData(); 
        
        // Design Lab category Id
        $designerToolCategoryId = Mage::getModel('core/variable')->loadByCode('DESIGNER_TOOL_CATEGORY_ID')->getValue('plain');
        $_categoryCollection = Mage::getModel('catalog/category')->load($designerToolCategoryId);
        //$_categoryCollection->getRequestPath();
        //echo '<pre>';print_r($_categoryCollection->getData());exit;              
        $params = $this->getDesignerProductParams($data['designersoftware_id']);
		$imageHtml='<a href="'.Mage::app()->getStore(1)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK). $_categoryCollection->getUrlPath().'?'. $params .'" target="_blank">Edit in Tool</a>';
       
        return $imageHtml;
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
