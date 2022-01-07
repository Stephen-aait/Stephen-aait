<?php
class Inky_Designersoftware_Block_Adminhtml_Designersoftware_Renderer_Drawarea extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $data = $row->getData();       
        
        //echo '<pre>';print_r($data);exit;
        $params = 'designCode/'.$data['style_design_code'].'/storeId/1/';
		$imageHtml='<a href="'.Mage::app()->getStore(1)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK). 'designersoftware/pdf/generate/'. $params .'" target="_blank">Design PDF</a>';
		$params = 'designCode/'.$data['style_design_code'].'/storeId/2/';
		$imageHtml.='<br><a href="'.Mage::app()->getStore(1)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK). 'designersoftware/pdf/generate/'. $params .'" target="_blank">Workshop PDF</a>';
        //$imageHtml.='<br>';
        //$paramsTemp = Mage::helper('designersoftware/product')->setParams($data['designersoftware_id'], 'template');  
        //$imageHtml.='<a href="'.Mage::app()->getStore(1)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK). $_categoryCollection->getUrlPath().'?'. $paramsTemp .'" target="_blank">Edit as Template</a>';
        
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
