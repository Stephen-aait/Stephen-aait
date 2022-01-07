<?php

class Inky_Designersoftware_Block_Adminhtml_Parts_Layers_Edit_Tab_Layer_Label extends Varien_Data_Form_Element_Abstract
{
    public function __construct($attributes=array())
    {
        parent::__construct($attributes);
        $this->setType('label');
    }

    public function getElementHtml()
    {
        $html = $this->getBold() ? '<strong>' : '';
        $html.= $this->getEscapedValue();
        $html.= $this->getBold() ? '</strong>' : '';
        $html.= $this->getAfterElementHtml();
        return $html;
    }

    public function getLabelHtml($idSuffix = ''){
        /*if (!is_null($this->getLabel())) {
            $html = '<label for="'.$this->getHtmlId() . $idSuffix . '" style="'.$this->getLabelStyle().'">'.$this->getLabel()
                . ( $this->getRequired() ? ' <span class="required">*</span>' : '' ).'</label>'."\n";
        }
        else {
            $html = '';
        }*/
        
        $html = '<ul class="messages">
					<li class="notice-msg">
						<ul>
							<li>
							Image type and information need to be specified for each store view.            </li>
						</ul>
					</li>
				</ul>';	
        return $html;
    }
}
