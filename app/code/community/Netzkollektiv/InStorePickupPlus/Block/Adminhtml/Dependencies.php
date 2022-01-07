<?php
class Netzkollektiv_InStorePickupPlus_Block_Adminhtml_Dependencies extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected $_addRowButtonHtml = array();
    protected $_removeRowButtonHtml = array();

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);

        $rows = array();
        if ($this->_getValue('shipping_method')) {
            foreach ($this->_getValue('shipping_method') as $i=>$f) {
                if ($i) {
                    $rows[] = $this->_getRowTemplateHtml($i);
                }
            }
        }
        $rows = implode($rows);
        
        $addButton = $this->_getAddRowButtonHtml(
            'dependency_container', 
            'dependency_template', 
            $this->__('Add dependency')
        );

        $html = <<<EOF
            <div class="grid" >
                <table style="display:none">
                    <tbody id="dependency_template">
                        {$this->_getRowTemplateHtml()}
                    </tbody>
            </table>
            
            <table class="border" cellspacing="0" cellpadding="0">
                <tbody id="dependency_container">
                    <tr class="headings">
                        <th>{$this->__('Shipping method')}</th>
                        <th>{$this->__('Payment method')}</th>
                        <th>&nbsp;</th>
                    </tr>
                    {$rows}
                </tbody>
            </table>
            </div>
            {$addButton}
EOF;
        
        return $html;
    }
    
    protected function _getShippingOptions($selectedIndex) {
        $options = '';
        foreach ( Mage::helper('instorepickupplus')->getShippingMethods() as $method) {
            if ( $method['value'] == $this->_getValue('shipping_method/'.$selectedIndex) ) {
                $options .= '<option value="'.$method['value'].'" selected>'.$method['label'].'</option>';
            } else {
                $options .= '<option value="'.$method['value'].'">'.$method['label'].'</option>';
            }
        }
        return $options;
    }
    
    protected function _getPaymentOptions($selectedIndex) {
        $options = '';
        foreach ( Mage::helper('instorepickupplus')->getPaymentMethods() as $method) {
            if( $method['value'] == $this->_getValue('payment_method/'.$selectedIndex) ) {
                $options .= '<option value="'.$method['value'].'" selected>'.$method['label'].'</option>';
            } else {
                $options .= '<option value="'.$method['value'].'">'.$method['label'].'</option>';
            }
        }
        return $options;
    }
    
    protected function _getRowTemplateHtml($selectedIndex = 0)
    {
        $html = <<<EOF
            <tr>
                <td>
                    <select class="option-control" style="width: 150px" value="" name="{$this->getElement()->getName()}[shipping_method][]" >
                        <option value=""></option>
                        {$this->_getShippingOptions($selectedIndex)}
                    </select>
                </td>
                <td>
                    <select class="option-control" style="width: 150px" value="" name="{$this->getElement()->getName()}[payment_method][]" >
                        <option value=""></option>
                        {$this->_getPaymentOptions($selectedIndex)}
                    </select>
                </td>
                <td>{$this->_getRemoveRowButtonHtml()}</td>
            </tr>
EOF;
        return $html;
    }
    
    protected function _getDisabled()
    {
        return $this->getElement()->getDisabled() ? ' disabled' : '';
    }
    
    protected function _getValue($key)
    {
        return $this->getElement()->getData('value/'.$key);
    }
    
    protected function _getAddRowButtonHtml($container, $template, $title='Add')
    {
        if (!isset($this->_addRowButtonHtml[$container])) {
            $this->_addRowButtonHtml[$container] = $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setType('button')
                    ->setClass('add '.$this->_getDisabled())
                    ->setLabel($this->__($title))
                    ->setOnClick("Element.insert($('".$container."'), {bottom: $('".$template."').innerHTML})")
                    ->setDisabled($this->_getDisabled())
                    ->toHtml();
        }
        return $this->_addRowButtonHtml[$container];
    }
    
    protected function _getRemoveRowButtonHtml($selector='tr', $title='Delete')
    {
        if (!$this->_removeRowButtonHtml) {
            $this->_removeRowButtonHtml = $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setType('button')
                    ->setClass('delete v-middle '.$this->_getDisabled())
                    ->setLabel($this->__($title))
                    ->setOnClick("Element.remove($(this).up('".$selector."'))")
                    ->setDisabled($this->_getDisabled())
                    ->toHtml();
        }
        return $this->_removeRowButtonHtml;
    }
}
