<?php



class Wyomind_Estimateddeliverydate_Model_System_Config_Source_Attributes {

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray() {
        
       
        /* R�cup�rer l'id du type d'attributs */
        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('core_read');
        $tableEet = $resource->getTableName('eav_entity_type');
        $select = $read->select()->from($tableEet)->where('entity_type_code=\'catalog_product\'');
        $data = $read->fetchAll($select);
        $typeId = $data[0]['entity_type_id'];

        function cmp($a, $b) {

            return ($a['frontend_label'] < $b['frontend_label']) ? -1 : 1;
        }

        /*  Liste des  attributs disponible dans la bdd */

        $attributesList = Mage::getResourceModel('eav/entity_attribute_collection')
                ->setEntityTypeFilter($typeId)
                ->addSetInfo()
                ->getData();
        
       
        usort($attributesList, "cmp");
        $attributes=array();

        foreach ($attributesList as $attribute) {


            if (!empty($attribute['frontend_label']) && $attribute['frontend_input']=="select")
                $attributes[]= array("value"=>$attribute['attribute_id'], 'label'=>$attribute['frontend_label']);
        }

        
        
        return $attributes;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray() {
        /* R�cup�rer l'id du type d'attributs */
        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('core_read');
        $tableEet = $resource->getTableName('eav_entity_type');
        $select = $read->select()->from($tableEet)->where('entity_type_code=\'catalog_product\'');
        $data = $read->fetchAll($select);
        $typeId = $data[0]['entity_type_id'];

        function cmp($a, $b) {

            return ($a['frontend_label'] < $b['frontend_label']) ? -1 : 1;
        }

        /*  Liste des  attributs disponible dans la bdd */

        $attributesList = Mage::getResourceModel('eav/entity_attribute_collection')
                ->setEntityTypeFilter($typeId)
                ->addSetInfo()
                ->getData();
        
       
        usort($attributesList, "cmp");
        $attributes=array();

        foreach ($attributesList as $attribute) {


            if (!empty($attribute['frontend_label']))
                $attributes[]= array("value"=>$attribute['attribute_id'], 'label'=>$attribute['frontend_label']);
        }

        
        
        return $attributes;
    }

}
