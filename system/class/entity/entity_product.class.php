<?php
// Class Object
// Name: entity_product
// Description: product

class entity_product extends entity
{
    function sync($parameter = array())
    {
        // set default sync parameters for index table
        $sync_parameter['sync_table'] = 'tbl_index_product';
        $sync_parameter['update_fields'] = array(
            'id' => 'tbl_entity_product.id',
            'name' => 'tbl_entity_product.name',
            'category_name' => 'tbl_entity_category.name',
            'description' => 'tbl_entity_product.description',
            'enter_time' => 'tbl_entity_product.enter_time',
            'update_time' => 'tbl_entity_product.update_time',
            'price' => 'tbl_entity_product.price',
            'category_id' => 'tbl_entity_category.id',
            'display_order' => 'tbl_entity_product.display_order',
            'active' => 'tbl_entity_product.active'
        );

        $sync_parameter['join'] = array(
            'LEFT JOIN tbl_entity_category ON tbl_entity_product.category_id = tbl_entity_category.id',
        );

        $sync_parameter['fulltext_key'] = array(
            'fulltext_keywords' => array('name','category_name','description')
        );

        $sync_parameter = array_merge($sync_parameter, $parameter);

        $result[] = parent::sync($sync_parameter);

        // set default sync parameters for view table
        $sync_parameter['sync_table'] = 'tbl_view_product';
        $sync_parameter['update_fields'] = array(
            'id' => 'tbl_entity_product.id',
            'friendly_uri' => 'tbl_entity_product.friendly_uri',
            'name' => 'tbl_entity_product.name',
            'alternate_name' => 'tbl_entity_product.alternate_name',
            'description' => 'tbl_entity_product.description',
            'image' => 'tbl_entity_product.image_id',
            'enter_time' => 'tbl_entity_product.enter_time',
            'update_time' => 'tbl_entity_product.update_time',
            'view_time' => '"'.date('Y-m-d H:i:s').'"',
            'category_name' => 'tbl_entity_category.name',
            'price' => 'tbl_entity_product.price',
            'display_order' => 'tbl_entity_product.display_order',
            'active' => 'tbl_entity_product.active'
        );

        $sync_parameter['join'] = array(
            'LEFT JOIN tbl_entity_category ON tbl_entity_product.category_id = tbl_entity_category.id',
        );

        $sync_parameter['fulltext_key'] = array();

        $sync_parameter = array_merge($sync_parameter, $parameter);

        $result[] = parent::sync($sync_parameter);

        return $result;

    }
}

?>