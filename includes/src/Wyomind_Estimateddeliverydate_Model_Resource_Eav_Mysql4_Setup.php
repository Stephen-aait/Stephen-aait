<?php

class Wyomind_Estimateddeliverydate_Model_Resource_Eav_Mysql4_Setup extends Mage_Eav_Model_Entity_Setup {

    public function getDefaultEntities() {
        $fields = Mage::helper("estimateddeliverydate")->getConfig();

        foreach ($fields as $type => $group) {
            foreach ($group['fields'] as $name => $field) {
                $attr[$type . '_' . $name] = array(
                    'group' => false,
                    'type' => 'varchar',
                    'input' => 'hidden',
                    'default' => '1',
                    'class' => '',
                    'backend' => '',
                    'frontend' => '',
                    'source' => '',
                    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => false,
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'visible_in_advanced_search' => false,
                    'unique' => false
                );
                $attr['use_config_' . $type . '_' . $name] = array(
                    'group' => false,
                    'type' => 'int',
                    'input' => 'hidden',
                    'default' => '1',
                    'note' => '',
                    'class' => '',
                    'backend' => 'catalog/product_attribute_backend_boolean',
                    'frontend' => '',
                    'source' => '',
                    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => false,
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'visible_in_advanced_search' => false,
                    'unique' => false
                );
                $attr['use_base_' . $type . '_' . $name] = array(
                    'group' => false,
                    'type' => 'int',
                    'input' => 'hidden',
                    'default' => '1',
                    'note' => '',
                    'class' => '',
                    'backend' => 'catalog/product_attribute_backend_boolean',
                    'frontend' => '',
                    'source' => '',
                    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => false,
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'visible_in_advanced_search' => false,
                    'unique' => false
                );
            }
        }

        $attr["back_to_stock"] = array(
            'group' => false,
            'type' => 'datetime',
            'input' => 'hidden',
            'default' => null,
            'note' => '',
            'class' => '',
            'frontend' => '',
            'source' => '',
            'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
            'visible' => true,
            'required' => false,
            'user_defined' => false,
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'visible_in_advanced_search' => false,
            'unique' => false,
           
        );

        return array(
            //LeadTime
            'catalog_product' => array(
                'entity_model' => 'catalog/product',
                'attribute_model' => 'catalog/resource_eav_attribute',
                'table' => 'catalog/product',
                'additional_attribute_table' => 'catalog/eav_attribute',
                'entity_attribute_collection' => 'catalog/product_attribute_collection',
                'attributes' => $attr
            )
        );
    }

}
