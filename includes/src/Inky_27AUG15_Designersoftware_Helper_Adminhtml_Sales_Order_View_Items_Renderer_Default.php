<?php

class Sparx_Designersoftware_Helper_Adminhtml_Sales_Order_View_Items_Renderer_Default extends Mage_Core_Helper_Abstract
{
	public function getColumnHtml($item, $columnName){
		//echo '<pre>s';print_r($item->getOrder()->getOrderCurrencyCode());
		$productId = $item->getProduct()->getId();
		
		$collection = Mage::getModel('designersoftware/designersoftware')->getCollection()
								->addFieldToFilter('product_id',$productId)
								->getFirstItem();
		
		$sizeCollection = Mage::getModel('designersoftware/sizes')->getCollection()
										->addFieldToFilter('sizes_id',$collection->getSizesInfo())
										->getFirstItem();
		
		switch($columnName){
			case 'sizes':	
			$sizeHtml = '<table cellspacing="0" class="qty-table">
							<tbody>
								<tr>
									<td></td>
									<td>'.$sizeCollection->getTitle().'</td>
								</tr>
							</tbody>
						</table>';	
								
				return $sizeHtml;
			break;
			case 'sizes_price':				
				
				$priceHtml = '<!--<span class="price-excl-tax">-->
								<span class="price">'. Mage::helper('core')->currency($sizeCollection->getPrice(),true,false) .'</span>';
								
								$baseCurrencyCode		= $item->getOrder()->getBaseCurrencyCode();
								$currentCurrencyCode	= $item->getOrder()->getOrderCurrencyCode();	
											
								if($baseCurrencyCode!=$currentCurrencyCode):
								
									$convertedPrice = Mage::getModel('designersoftware/style')->convertBaseToCurrentCurrency($sizeCollection->getPrice(),$baseCurrencyCode,$currentCurrencyCode);
									$currency_symbol = Mage::app()->getLocale()->currency( $currentCurrencyCode )->getSymbol();
									$convertedPrice = $currency_symbol.round($convertedPrice,2);
									
								$priceHtml .= '<br>								
									<span class="price">['.$convertedPrice.']</span>';                            
								endif;
					$priceHtml .= '<!--</span>-->';
                            
				return $priceHtml;
				//return $productId;
			break;
			default:
		}
		
	}
}
