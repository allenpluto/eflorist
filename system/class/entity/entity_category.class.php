<?php
// Class Object
// Name: entity_category
// Description: (business) category, mainly follow the standard of schema.org

class entity_category extends entity
{
    function __construct($value = Null, $parameter = array())
    {
        $default_parameter = [
            'relational_fields'=>[
                'organization'=>[],
                'gallery'=>[]
            ]
        ];
        $parameter = array_merge($default_parameter, $parameter);
        return parent::__construct($value, $parameter);
    }

    function sync($parameter = array())
    {
        // set default sync parameters for index table
        $sync_parameter['sync_table'] = 'tbl_index_category';
        $sync_parameter['update_fields'] = array(
            'id' => 'tbl_entity_category.id',
            'name' => 'tbl_entity_category.name',
            'enter_time' => 'tbl_entity_category.enter_time',
            'update_time' => 'tbl_entity_category.update_time',
            'keywords' => 'tbl_entity_category.keywords',
            'status' => 'tbl_entity_category.status',
            'parent' => 'tbl_entity_category.parent_id',
            'sibling' => 'GROUP_CONCAT(DISTINCT tbl_sibling.id)',
            'child' => 'GROUP_CONCAT(DISTINCT tbl_child.id)',
            'organization_count' => 'COUNT(DISTINCT tbl_rel_category_to_organization.organization_id)',
        );


        $sync_parameter['join'] = array(
            'LEFT JOIN tbl_rel_category_to_organization ON tbl_entity_category.id = tbl_rel_category_to_organization.category_id',
            'LEFT JOIN tbl_entity_category tbl_sibling ON tbl_entity_category.parent_id = tbl_sibling.parent_id',
            'LEFT JOIN tbl_entity_category tbl_child ON tbl_entity_category.id = tbl_child.parent_id'
        );

        $sync_parameter['where'] = array(
            'tbl_entity_category.status = "A"'
        );

        $sync_parameter['group'] = array(
            'tbl_entity_category.id'
        );

        $sync_parameter['fulltext_key'] = array();

        $sync_parameter = array_merge($sync_parameter, $parameter);

        $result[] = parent::sync($sync_parameter);

        // set default sync parameters for view table
        $sync_parameter['sync_table'] = 'tbl_view_category';
        $sync_parameter['update_fields'] = array(
            'id' => 'tbl_entity_category.id',
            'friendly_uri' => 'tbl_entity_category.friendly_uri',
            'name' => 'tbl_entity_category.name',
            'alternate_name' => 'tbl_entity_category.alternate_name',
            'description' => 'tbl_entity_category.description',
            'enter_time' => 'tbl_entity_category.enter_time',
            'update_time' => 'tbl_entity_category.update_time',
            'view_time' => '"'.date('Y-m-d H:i:s').'"',
            'keywords' => 'tbl_entity_category.keywords',
            'content' => 'tbl_entity_category.content',
            'status' => 'tbl_entity_category.status',
            'parent_id' => 'tbl_entity_category.parent_id',
            'scoopit_uri' => 'tbl_entity_category.scoopit_uri',
            'schema_itemtype' => 'tbl_entity_category.schema_itemtype',
            'organization_count' => 'COUNT(DISTINCT tbl_rel_category_to_organization.organization_id)',
            'image' => 'GROUP_CONCAT(DISTINCT tbl_rel_gallery_to_image.image_id)'
        );


        $sync_parameter['join'] = array(
            'LEFT JOIN tbl_rel_category_to_organization ON tbl_entity_category.id = tbl_rel_category_to_organization.category_id',
            'LEFT JOIN tbl_rel_category_to_gallery ON tbl_entity_category.id = tbl_rel_category_to_gallery.category_id',
            'LEFT JOIN tbl_rel_gallery_to_image ON tbl_rel_category_to_gallery.gallery_id = tbl_rel_gallery_to_image.gallery_id'
        );

        $sync_parameter['where'] = array(
            'tbl_entity_category.status = "A"'
        );

        $sync_parameter['group'] = array(
            'tbl_entity_category.id'
        );

        $sync_parameter['fulltext_key'] = array();

        $sync_parameter = array_merge($sync_parameter, $parameter);

        $result[] = parent::sync($sync_parameter);

        return $result;

    }
}

?>