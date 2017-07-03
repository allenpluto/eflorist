<?php
// Class Object
// Name: entity_web_page
// Description: Stored Web Pages

// image_id in image_object reference to source image. One source image may have zero to multiple thumbnail (cropped versions) for different scenario. Only source image may save exifData, any thumbnail can be regenerated using source image exifData and 
class entity_web_page extends entity
{
    function sync($parameter = array())
    {
        $sync_parameter = array();

        // set default sync parameters for index table
        //$parameter['sync_table'] = str_replace('entity','index',$this->parameter['table']);
        $sync_parameter['sync_table'] = 'tbl_view_web_page';
        $sync_parameter['update_fields'] = array(
            'id' => 'tbl_entity_web_page.id',
            'friendly_uri' => 'tbl_entity_web_page.friendly_uri',
            'name' => 'tbl_entity_web_page.name',
            'alternate_name' => 'tbl_entity_web_page.alternate_name',
            'description' => 'tbl_entity_web_page.description',
            'image' => 'tbl_entity_web_page.image_id',
            'enter_time' => 'tbl_entity_web_page.enter_time',
            'update_time' => 'tbl_entity_web_page.update_time',
            'view_time' => '"'.date('Y-m-d H:i:s').'"',
            'meta_keywords' => 'tbl_entity_web_page.meta_keywords',
            'page_title' => 'tbl_entity_web_page.page_title',
            'page_content' => 'tbl_entity_web_page.page_content',
            'extra_field' => 'tbl_entity_web_page.extra_field'
        );

        $sync_parameter = array_merge($sync_parameter, $parameter);

        if ($GLOBALS['db']) $db = $GLOBALS['db'];
        else $db = new db;

        if (!isset($sync_parameter['sync_type']))
        {
            $sync_parameter['sync_type'] = 'differential_sync';
        }
        return parent::sync($sync_parameter);
    }
}