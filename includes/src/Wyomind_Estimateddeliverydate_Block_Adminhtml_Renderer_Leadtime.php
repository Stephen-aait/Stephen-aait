<?php

class Wyomind_Estimateddeliverydate_Block_Adminhtml_Renderer_Leadtime extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action {

    public function render(Varien_Object $row) {
        return "+ <input value='".$row->getValue()."' name='leadtime[".$row->getAttributeId()."][".$row->getValueId()."]'/>".$this->__('day(s)');
       
    }

}
