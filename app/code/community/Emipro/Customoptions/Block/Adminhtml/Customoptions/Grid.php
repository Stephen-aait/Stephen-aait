<?php
class Emipro_Customoptions_Block_Adminhtml_Customoptions_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId("customoptionsGrid");
        $this->setDefaultSort("entity_id");
        $this->setDefaultDir("DESC");
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
		$feature_id = Mage::getModel('catalog/product')->getIdBySku('customoptionmaster');
        $collection = Mage::getModel("emipro_customoptions/product_option")->getCollection();
        $collection->getSelect()->join(
			Mage::getConfig()->getTablePrefix() . 'emipro_product_option_title', 'main_table.option_id =' . Mage::getConfig()->getTablePrefix() . 'emipro_product_option_title.option_id', array("title" => "title"))
				->group(Mage::getConfig()->getTablePrefix() ."emipro_product_option_title.option_id");
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
		$optionType=array();
		$types=Mage::getSingleton('adminhtml/system_config_source_product_options_type')->toOptionArray();
		foreach($types as $type)
		{
			foreach($type as $key => $value)
			{
				foreach($value as $k => $v)
				{
					$optionType[$v["value"]]=$v["label"];
				}
			}
		}
        $this->addColumn("option_id", array(
            "header" => Mage::helper("emipro_customoptions")->__("ID"),
            "align" => "right",
            "width" => "10px",
            "type" => "number",
            "index" => "option_id",
        ));
        $this->addColumn("title", array(
            "header" => Mage::helper("emipro_customoptions")->__("Title"),
            "align" => "left",
            "width" => "150px",
            "type" => "text",
            "index" => "title",
        ));
        $this->addColumn("sku", array(
            "header" => Mage::helper("emipro_customoptions")->__("SKU"),
            "align" => "left",
            "width" => "150px",
            "type" => "text",
            "index" => "sku",
        ));

        $this->addColumn("type", array(
            "header" => Mage::helper("emipro_customoptions")->__("Type"),
            "align" => "left",
            "width" => "100px",
            "type" => "options",
            "index" => "type",
            "options"=>$optionType,
        ));
         $this->addColumn("action_product", array(
            "header" => Mage::helper("emipro_customoptions")->__("Action"),
            "index" => "type",
            "width" => "100px",
            "filter"=>  false,
            "renderer"=>"Emipro_Customoptions_Block_Adminhtml_Customoptions_Grid_Renderer_EditProduct",
            "sortable"=>false,
        ));
        $this->addColumn("action_category", array(
            "header" => Mage::helper("emipro_customoptions")->__("Action"),
            "index" => "type",
            "width" => "100px",
            "filter"=>  false,
            "renderer"=>"Emipro_Customoptions_Block_Adminhtml_Customoptions_Grid_Renderer_EditCategory",
             "sortable"=>false,
        ));
        $this->addColumn("action_rmvsku", array(
            "header" => Mage::helper("emipro_customoptions")->__("Action"),
            "index" => "type",
            "width" => "100px",
            "filter"=>  false,
            "renderer"=>"Emipro_Customoptions_Block_Adminhtml_Customoptions_Grid_Renderer_RemoveProduct",
             "sortable"=>false,
        ));
       
        return parent::_prepareColumns();
    }
    public function getRowUrl($row)
    {
		return $this->getUrl("*/*/edit",array("id"=>$row->getId()));
	}

}
