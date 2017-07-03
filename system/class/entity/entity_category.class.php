<?php
// Class Object
// Name: entity_category
// Description: (business) category, mainly follow the standard of schema.org

class entity_category extends entity
{
    function sync($parameter = array())
    {
        // set default sync parameters for view table
        $sync_parameter['sync_table'] = 'tbl_view_category';
        $sync_parameter['update_fields'] = array(
            'id' => 'tbl_entity_category.id',
            'friendly_uri' => 'tbl_entity_category.friendly_uri',
            'name' => 'tbl_entity_category.name',
            'alternate_name' => 'tbl_entity_category.alternate_name',
            'description' => 'tbl_entity_category.description',
            'image' => 'tbl_entity_category.image_id',
            'enter_time' => 'tbl_entity_category.enter_time',
            'update_time' => 'tbl_entity_category.update_time',
            'view_time' => '"'.date('Y-m-d H:i:s').'"',
            'display_order' => 'tbl_entity_category.display_order'
        );

        $sync_parameter['fulltext_key'] = array();

        $sync_parameter = array_merge($sync_parameter, $parameter);

        $result[] = parent::sync($sync_parameter);

        return $result;

    }
}

?>