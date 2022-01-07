<?php

/**
 * Iksanika llc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.iksanika.com/products/IKS-LICENSE.txt
 *
 * @category   Iksanika
 * @package    Iksanika_Productupdater
 * @copyright  Copyright (c) 2013 Iksanika llc. (http://www.iksanika.com)
 * @license    http://www.iksanika.com/products/IKS-LICENSE.txt
 */

class Iksanika_Productupdater_Block_Widget_Grid_Column_Renderer_GroupPrice
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Price
{
    
    private $f; //Mage_Adminhtml_Block_Widget_Form
    public $_customerGroups = null;
        
    public function __construct()
    {
//        $this->f = new Mage_Adminhtml_Block_Widget_Form;
    }
    
    // fake "extends C" using magic function
    public function __call($method, $args)
    {
//        $this->f->$method($args[0]);
    }
    
    public $product = null;
    
    public function getProduct($productId = null)
    {
        if($productId && !$this->product)
        {
            $this->product = Mage::getModel('catalog/product')->load($productId);
        }else if($productId && $this->product && $productId != $this->product->getId())
        {
            $this->product = Mage::getModel('catalog/product')->load($productId);
        }
        return $this->product;
    }
    
    public function render(Varien_Object $row, $isAssociated = false) 
    {
        $product        =   $this->getProduct($row->getData('entity_id'));
        
        $_htmlId        =   $row->getData('entity_id');
        $_readonly      =   false;
        
        //$tierPrices = $product->getTierPrice();
        $groupPrices    =   $product->getData('group_price');
        
        $_htmlClass     =   'intput-text';
        $_htmlName      =   'group_price' . ($isAssociated ? '_assoc' : '');

        $html = '
            <table cellspacing="0" class="data border" id="group_prices_table">
                <colgroup>
                    '.(($_showWebsite) ? '<col width="135" />': '').'
                    <col width="120">
                    <col width="95">
                    <col width="95">
                    <col width="1">
                </colgroup>
                <thead>
                    <tr class="headings">
                        <th '.((!$_showWebsite) ? 'style="display:none"' : '').'>'.Mage::helper('sales')->__('Website').'</th>
                        <th>'.Mage::helper('catalog')->__('Customer Group').'</th>
                        <th>'.Mage::helper('catalog')->__('Price').'</th>
                        <th class="last">'.Mage::helper('catalog')->__('Action').'</th>
                    </tr>
                </thead>
        ';
    
        $customerGroups = $this->getCustomerGroups();
        
        $html .= '
                <tbody id="'.$_htmlName.'_'.$_htmlId.'_container"></tbody>

                <tfoot>
                    <tr>
                        <td style="display:none"></td>
                        <td colspan="4" class="a-right"><button id="id_add_'.$_htmlName.'" title="'.Mage::helper('catalog')->__('Add Group Price').'" type="button" class="scalable add" onclick="return groupPriceControl'. ($isAssociated ? 'Assoc' : '').$_htmlId.'.addItem()" style=""><span>'.Mage::helper('catalog')->__('Add Group Price').'</span></button></td>
                    </tr>
                </tfoot>
            </table>
        ';
        

        $html .= '
<script type="text/javascript" id="productGPScript'.$_htmlId.'">
//<![CDATA[
var groupPriceRowTemplate'.($isAssociated ? 'Assoc' : '').$_htmlId.' = \'<tr>\'
    + \'<td'.((!$_showWebsite) ? ' style="display:none"' : '').'>\'
    + \'<select class="'.$_htmlClass.' required-entry" name="'.$_htmlName.'[{{index}}][website_id]" id="'.$_htmlName.'_row_{{index}}_website">\'';
    foreach ($this->getWebsites() as $_websiteId => $_info)
    {
        $html .= '+ \'<option value="'.$_websiteId.'">'.$this->jsQuoteEscape($this->htmlEscape($_info['name'])).( (!empty($_info['currency'])) ? ('['.$this->htmlEscape($_info['currency']).']') : '').'</option>\'';
    }
    $html .='
    + \'</select></td>\'
    + \'<td><select class="'.$_htmlClass.' custgroup required-entry" name="'.$_htmlName.'[{{index}}][cust_group]" id="'.$_htmlName.'_row_{{index}}_cust_group">\'';
    $html .= '+\'<option value="32000">'.Mage::helper('catalog')->__('ALL GROUPS').'</option>\'';
    foreach ($customerGroups as $_groupId=>$_groupName)
    {
        $html .= '+\'<option value="'.$_groupId.'">'.$this->jsQuoteEscape($this->htmlEscape($_groupName)).'</option>\'';
    }
    $html .= '+ \'</select></td>\'
    + \'<td><input class="'.$_htmlClass.' required-entry '.$_priceValueValidation.'" type="text" name="'.$_htmlName.'[{{index}}][price]" value="{{price}}" id="'.$_htmlName.'_row_{{index}}_price" /></td>\'
    + \'<td class="last"><input type="hidden" name="'.$_htmlName.'[{{index}}][delete]" class="delete" value="" id="'.$_htmlName.'_row_{{index}}_delete" />\'
    + \'<button title="'.Mage::helper('catalog')->__("Delete Group").'" type="button" class="scalable delete icon-btn delete-product-option" id="'.$_htmlName.'_row_{{index}}_delete_button" onclick="return groupPriceControl'.$_htmlId.'.deleteItem(event);">\'
    + \'<span><span><span>'.Mage::helper('catalog')->__("Delete").'</span></span></span></button></td>\'
    + \'</tr>\';

var groupPriceControl'.($isAssociated ? 'Assoc' : '').$_htmlId.' = {
    template: new Template(groupPriceRowTemplate'.($isAssociated ? 'Assoc' : '').$_htmlId.', new RegExp(\'(^|.|\\\\r|\\\\n)({{\\\\s*(\\\\w+)\\\\s*}})\', "")),
    itemsCount'.$_htmlId.': 0,
    addItem : function () {
        '.(($_readonly) ? '
        if (arguments.length < 3) {
            return;
        } ' : '').'
        var data = {
            website_id: \''.$this->getDefaultWebsite().'\',
            group: \''.$this->getDefaultCustomerGroup().'\',
            qty: \'\',
            price: \'\',
            readOnly: 0,
            index: this.itemsCount'.$_htmlId.'++
        };

        if(arguments.length >= 3) {
            data.website_id = arguments[0];
            data.group      = arguments[1];
            data.price      = arguments[2];
        }
        if (arguments.length == 4) {
            data.readOnly = arguments[3];
        }

        Element.insert($(\''.$_htmlName.'_'.$_htmlId.'_container\'), {
            bottom : this.template.evaluate(data)
        });
        $j(\'#product'.$_htmlId.' #'.$_htmlName.'_row_\'+data.index+\'_cust_group\').val(data.group);
        $j(\'#product'.$_htmlId.' #'.$_htmlName.'_row_\'+data.index+\'_website\').val(data.website_id);
        //$(\''.$_htmlName.'_row_\' + data.index + \'_cust_group\').value = data.group;
        //$(\''.$_htmlName.'_row_\' + data.index + \'_website\').value    = data.website_id;
        '.(($this->isShowWebsiteColumn() && !$this->isAllowChangeWebsite()) ? '
        var wss = $j(\'#product'.$_htmlId.' #group_row_\'+data.index+\'_website\');
        //var wss = $(\''.$_htmlName.'_row_\' + data.index + \'_website\');
        var txt = wss.options[wss.selectedIndex].text;

        wss.insert({after:\'<span class="website-name">\' + txt + \'</span>\'});
        wss.hide();
        ' : '').'

        if (data.readOnly == \'1\') {
            [\'website\', \'cust_group\', \'qty\', \'price\', \'delete\'].each(function(idx){
                $(\''.$_htmlName.'_row_\'+data.index+\'_\'+idx).disabled = true;
            });
            $(\''.$_htmlName.'_row_\'+data.index+\'_delete_button\').hide();
        }

        '.(($_readonly) ? '
        $(\''.$_htmlName.'_'.$_htmlId.'_container\').select(\'input\', \'select\').each(this.disableElement);
        $(\''.$_htmlName.'_'.$_htmlId.'_container\').up(\'table\').select(\'button\').each(this.disableElement);
        ':' 
        $(\''.$_htmlName.'_'.$_htmlId.'_container\').select(\'input\', \'select\').each(function(el){ Event.observe(el, \'change\', el.setHasChanges.bind(el)); });
        '). '
    },
    disableElement: function(el) {
        el.disabled = true;
        el.addClassName(\'disabled\');
    },
    deleteItem: function(event) {
        var tr = Event.findElement(event, \'tr\');
        if (tr) {
            Element.select(tr, \'.delete\').each(function(elem){elem.value=\'1\'});
            Element.select(tr, [\'input\', \'select\']).each(function(elem){elem.hide()});
            Element.hide(tr);
            Element.addClassName(tr, \'no-display template\');
        }
        return false;
    }
}; ';
    
    
/*</script> */
         
//        return $html;        
   /*
        $html .= '
<script type="text/javascript">
//<![CDATA[
var groupPriceRowTemplate'.$_htmlId.' = \'<tr>\'
    + \'<td'.((!$_showWebsite) ? ' style="display:none"' : '').'>\'
    + \'<select class="'.$_htmlClass.' required-entry" name="'.$_htmlName.'[{{index}}][website_id]" id="'.$_htmlName.'_row_{{index}}_website">\'';
    foreach ($this->getWebsites() as $_websiteId => $_info)
    {
        $html .= '+ \'<option value="'.$_websiteId.'">'.$this->jsQuoteEscape($this->htmlEscape($_info['name'])).( (!empty($_info['currency'])) ? ('['.$this->htmlEscape($_info['currency']).']') : '').'</option>\'';
    }
    $html .='
    + \'</select></td>\'
    + \'<td><select class="'.$_htmlClass.' custgroup required-entry" name="'.$_htmlName.'[{{index}}][cust_group]" id="'.$_htmlName.'_row_{{index}}_cust_group">\'';
    $html .= '+\'<option value="32000">'.Mage::helper('catalog')->__('ALL GROUPS').'</option>\'';
    foreach ($customerGroups as $_groupId=>$_groupName)
    {
        $html .= '+\'<option value="'.$_groupId.'">'.$this->jsQuoteEscape($this->htmlEscape($_groupName)).'</option>\'';
    }
    $html .= '+ \'</select></td>\'
    + \'<td><input class="'.$_htmlClass.' required-entry '.$_priceValueValidation.'" type="text" name="'.$_htmlName.'[{{index}}][price]" value="{{price}}" id="'.$_htmlName.'_row_{{index}}_price" /></td>\'
    + \'<td class="last"><input type="hidden" name="'.$_htmlName.'[{{index}}][delete]" class="delete" value="" id="'.$_htmlName.'_row_{{index}}_delete" />\'
    + \'<button title="'.Mage::helper('catalog')->__("Delete Group").'" type="button" class="scalable delete icon-btn delete-product-option" id="'.$_htmlName.'_row_{{index}}_delete_button" onclick="return groupPriceControl'.$_htmlId.'.deleteItem(event);">\'
    + \'<span><span><span>'.Mage::helper('catalog')->__("Delete").'</span></span></span></button></td>\'
    + \'</tr>\';

var groupPriceControl'.$_htmlId.' = {
    template: new Template(groupPriceRowTemplate'.$_htmlId.', new RegExp(\'(^|.|\\\\r|\\\\n)({{\\\\s*(\\\\w+)\\\\s*}})\', "")),
    itemsCount'.$_htmlId.': 0,
    addItem : function () {
        '.(($_readonly) ? '
        if (arguments.length < 3) {
            return;
        } ' : '').'
        var data = {
            website_id: \''.$this->getDefaultWebsite().'\',
            group: \''.$this->getDefaultCustomerGroup().'\',
            qty: \'\',
            price: \'\',
            readOnly: 0,
            index: this.itemsCount'.$_htmlId.'++
        };

        if(arguments.length >= 3) {
            data.website_id = arguments[0];
            data.group      = arguments[1];
            data.price      = arguments[2];
        }
        if (arguments.length == 4) {
            data.readOnly = arguments[3];
        }

        Element.insert($(\''.$_htmlName.'_'.$_htmlId.'_container\'), {
            bottom : this.template.evaluate(data)
        });
        $j(\'#product'.$_htmlId.' #'.$_htmlName.'_row_\'+data.index+\'_cust_group\').val(data.group);
        $j(\'#product'.$_htmlId.' #'.$_htmlName.'_row_\'+data.index+\'_website\').val(data.website_id);
        //$(\''.$_htmlName.'_row_\' + data.index + \'_cust_group\').value = data.group;
        //$(\''.$_htmlName.'_row_\' + data.index + \'_website\').value    = data.website_id;
        '.(($this->isShowWebsiteColumn() && !$this->isAllowChangeWebsite()) ? '
        var wss = $j(\'#product'.$_htmlId.' #group_row_\'+data.index+\'_website\');
        //var wss = $(\''.$_htmlName.'_row_\' + data.index + \'_website\');
        var txt = wss.options[wss.selectedIndex].text;

        wss.insert({after:\'<span class="website-name">\' + txt + \'</span>\'});
        wss.hide();
        ' : '').'

        if (data.readOnly == \'1\') {
            [\'website\', \'cust_group\', \'qty\', \'price\', \'delete\'].each(function(idx){
                $(\''.$_htmlName.'_row_\'+data.index+\'_\'+idx).disabled = true;
            });
            $(\''.$_htmlName.'_row_\'+data.index+\'_delete_button\').hide();
        }

        '.(($_readonly) ? '
        $(\''.$_htmlName.'_'.$_htmlId.'_container\').select(\'input\', \'select\').each(this.disableElement);
        $(\''.$_htmlName.'_'.$_htmlId.'_container\').up(\'table\').select(\'button\').each(this.disableElement);
        ':' 
        $(\''.$_htmlName.'_'.$_htmlId.'_container\').select(\'input\', \'select\').each(function(el){ Event.observe(el, \'change\', el.setHasChanges.bind(el)); });
        '). '
    },
    disableElement: function(el) {
        el.disabled = true;
        el.addClassName(\'disabled\');
    },
    deleteItem: function(event) {
        var tr = Event.findElement(event, \'tr\');
        if (tr) {
            Element.select(tr, \'.delete\').each(function(elem){elem.value=\'1\'});
            Element.select(tr, [\'input\', \'select\']).each(function(elem){elem.hide()});
            Element.hide(tr);
            Element.addClassName(tr, \'no-display template\');
        }
        return false;
    }
}; ';
*/    
//        foreach($groupPrices as $groupPrice)
        foreach($groupPrices as $_item)
        {
            $html .= '
                    groupPriceControl'.($isAssociated ? 'Assoc' : '').$_htmlId.'.addItem(\''.$_item['website_id'].'\', \''.$_item['cust_group'].'\', \''.sprintf('%.2f', $_item['price']).'\', '.(int)!empty($_item['readonly']).');';
        }
        if ($_readonly)
        {
            $html .= '$(\''.$_htmlName.'_'.$_htmlId.'_container\').up(\'table\').select(\'button\')
                .each(groupPriceControl'. ($isAssociated ? 'Assoc' : '').$_htmlId.'.disableElement);';
        }

        $html .= '
        //]]>
        </script>';
        
        return $html;        
    }
    
    public function isShowWebsiteColumn()
    {
        if ($this->isScopeGlobal() || Mage::app()->isSingleStoreMode()) {
            return false;
        }
        return true;
    }
    
    public function isAllowChangeWebsite()
    {
        if (!$this->isShowWebsiteColumn() || $this->getProduct()->getStoreId()) {
            return false;
        }
        return true;
    }
 
    public function getWebsites()
    {
        if (!is_null($this->_websites)) {
            return $this->_websites;
        }

        $this->_websites = array(
            0 => array(
                'name' => Mage::helper('catalog')->__('All Websites'),
                'currency' => Mage::app()->getBaseCurrencyCode()
            )
        );

        if (!$this->isScopeGlobal() && $this->getProduct()->getStoreId()) {
            /** @var $website Mage_Core_Model_Website */
            $website = Mage::app()->getStore($this->getProduct()->getStoreId())->getWebsite();

            $this->_websites[$website->getId()] = array(
                'name' => $website->getName(),
                'currency' => $website->getBaseCurrencyCode()
            );
        } elseif (!$this->isScopeGlobal()) {
            $websites = Mage::app()->getWebsites(false);
            $productWebsiteIds  = $this->getProduct()->getWebsiteIds();
            foreach ($websites as $website) {
                /** @var $website Mage_Core_Model_Website */
                if (!in_array($website->getId(), $productWebsiteIds)) {
                    continue;
                }
                $this->_websites[$website->getId()] = array(
                    'name' => $website->getName(),
                    'currency' => $website->getBaseCurrencyCode()
                );
            }
        }

        return $this->_websites;
    }

    public function getCustomerGroups($groupId = null)
    {
        if ($this->_customerGroups === null) {
            if (!Mage::helper('catalog')->isModuleEnabled('Mage_Customer')) {
                return array();
            }
            $collection = Mage::getModel('customer/group')->getCollection();
            $this->_customerGroups = $this->_getInitialCustomerGroups();

            foreach ($collection as $item) {
                /** @var $item Mage_Customer_Model_Group */
                $this->_customerGroups[$item->getId()] = $item->getCustomerGroupCode();
            }
        }
        
        if ($groupId !== null) {
            return isset($this->_customerGroups[$groupId]) ? $this->_customerGroups[$groupId] : array();
        }
        return $this->_customerGroups;
    }
    
    protected function _getInitialCustomerGroups()
    {
        return array();
    }
    
    public function getDefaultCustomerGroup()
    {
        return Mage_Customer_Model_Group::CUST_GROUP_ALL;
    }
    
    public function getDefaultWebsite()
    {
        if ($this->isShowWebsiteColumn() && !$this->isAllowChangeWebsite()) {
            return Mage::app()->getStore($this->getProduct()->getStoreId())->getWebsiteId();
        }
        return 0;
    }
    

}
