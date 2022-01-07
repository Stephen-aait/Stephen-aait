<?php

class Wyomind_Estimateddeliverydate_Block_Adminhtml_Manageleadtimes_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {

        parent::__construct();
        $this->setId('estimateddeliverydateGrid');
        $this->setDefaultSort('attribute_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('estimateddeliverydate/attributes')->getCollection();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('attribute_id', array(
            'header' => Mage::helper('estimateddeliverydate')->__('Attribute'),
            'align' => 'right',
            'width' => '300px',
            'index' => 'attribute_id',
            'filter' => false,
            'renderer' => 'Wyomind_Estimateddeliverydate_Block_Adminhtml_Renderer_Attribute',
        ));
        $this->addColumn('value_id', array(
            'header' => Mage::helper('estimateddeliverydate')->__('Value'),
            'align' => 'left',
            'width' => '300px',
            'index' => 'value_id',
            'filter' => false,
            'renderer' => 'Wyomind_Estimateddeliverydate_Block_Adminhtml_Renderer_Value',
        ));

        $this->addColumn('value', array(
            'header' => Mage::helper('estimateddeliverydate')->__('Leadtime'),
            'align' => 'left',
            'index' => 'value',
            'filter' => false,
            'renderer' => 'Wyomind_Estimateddeliverydate_Block_Adminhtml_Renderer_Leadtime',
        ));



        return parent::_prepareColumns();
    }

    

}