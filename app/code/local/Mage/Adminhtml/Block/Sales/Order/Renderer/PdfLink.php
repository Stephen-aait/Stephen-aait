<?php

class Mage_Adminhtml_Block_Sales_Order_Renderer_PdfLink extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {		
        
        $db = Mage::getSingleton('core/resource')->getConnection('core_write');
        $orderId = $row->getId();
        $order = Mage::getModel('sales/order')->load($orderId);
        $items = $order->getAllItems();
        $itemcount = count($items);
        $productIds = array();
        foreach ($items as $itemId => $item) {
            //$productIds[] = $item->getProductId();
            $itemInfoRequest = $item->getProductOptions();
			//echo '<pre>';print_r($order->getData());exit;
			//$designCode = $itemInfoRequest['info_buyRequest']['options']['7'];
			$designCode = $itemInfoRequest['options']['0']['value'];
			
			if( !empty($designCode) ){
				echo '<a target="_blank" href="'. Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'designersoftware/pdf/generate/designCode/'.$designCode.'/storeId/1/orderId/'. $orderId .'">Customer PDF</a><br>';
				echo '<a target="_blank" href="'. Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'designersoftware/pdf/generate/designCode/'.$designCode.'/storeId/2">Workshop PDF</a><br>';
			}
			//echo '<a href="javascript:void(0);" href="'. Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'designersoftware/pdf/designCode/'.$designCode.'/storeId/1/' .'">Customer PDF</a><br>';			
        }
        
        // fetch increment id-------------
        $orderData = $order->getData();
        $increment_id = $orderData['increment_id'];        
        //$url=str_replace('/index.php','',Mage::getUrl('',array('_secure'=>true)));
        
       $url=Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
        
        /*if (count($productIds) > 0){
           $directoryName = Mage::getBaseDir().'/media/inky/pdf/' . $increment_id . ".zip";
            ?>
            <div id="pdfDiv_<?php echo $orderId ?>">
                <?php
                if (!is_file($directoryName)) {
                    echo '<a href="javascript:void(0);" onclick="generate_pdf(' . $orderId . ',\'1\');">Generate PDF</a>';
                } else {
                    echo '<a style="display: block;" title="Order Detail" href="' . $url . 'designertool/pdffiles/download.php?fileName=' . $increment_id . '.zip">Download PDF</a></br>';
                    echo '<a href="javascript:void(0);" onclick="generate_pdf(' . $orderId . ',\'0\');"> Delete PDF </a>';
                }
                ?>
            </div>
            <script type="text/javascript">
                var xmlHttp;
                function getobject()
                {
                    var xmlHttp=null;
                    try
                    {
                        xmlHttp=new XMLHttpRequest;
                    }
                    catch(e)
                    {
                        try
                        {
                            xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
                        }
                        catch(e)
                        {
                            xmlHttp=new ActiveXObject("Microsoft.XMLHTTP")	;
                        }
                    }
                    return xmlHttp;
                }

                function generate_pdf(oid,flag)
                {
                    xmlhttp=getobject();
                    xmlhttp.onreadystatechange=function()
                    {
                        if (xmlhttp.readyState==1){
                            document.getElementById("pdfDiv_"+oid).innerHTML='<img src="<?=$url?>media/files/moduleimages/loading.gif">';
                        }
                        if (xmlhttp.readyState==4)
                        {
                            //alert(xmlhttp.responseText);
                            document.getElementById("pdfDiv_"+oid).innerHTML=xmlhttp.responseText;
                        }
                    }
                    //alert("<?= $url ?>designertool/pdffiles/genpdf.php?orderId="+oid+"&url="+"<?= $url ?>"+"&flag="+flag);
                    xmlhttp.open("GET","<?= $url ?>designertool/pdffiles/genpdf.php?orderId="+oid+"&url="+"<?= $url ?>"+"&flag="+flag,true);
                    xmlhttp.send(null);
                }
            </script>
            <?php
        }*/
    }

}
?>

