<?php
// Class Object
// Name: entity_css
// Description: Style Source File, store style content by modules, plugin and content parts

class entity_css extends entity
{
    function __construct($value = Null, $parameter = array())
    {
        include_once(PATH_PREFERENCE.'image'.FILE_EXTENSION_INCLUDE);

        $default_parameter = [
            'store_data'=>true
        ];
        $parameter = array_merge($default_parameter, $parameter);
        parent::__construct($value, $parameter);

        if (!$this->parameter['store_data'])
        {
            unset($this->parameter['table_fields']['data']);
        }

        return $this;
    }

    function sync($parameter = array())
    {
        $sync_parameter = array();

        // set default sync parameters for index table
        //$parameter['sync_table'] = str_replace('entity','index',$this->parameter['table']);
        $sync_parameter['sync_table'] = 'tbl_view_image';
        $sync_parameter['update_fields'] = array(
            'id' => 'tbl_entity_css.id',
            'friendly_uri' => 'tbl_entity_css.friendly_uri',
            'name' => 'tbl_entity_css.name',
            'alternate_name' => 'tbl_entity_css.alternate_name',
            'description' => 'tbl_entity_css.description',
            'enter_time' => 'tbl_entity_css.enter_time',
            'update_time' => 'tbl_entity_css.update_time',
            'source_code' => 'tbl_entity_css.source_file',
            'min_source_code' => '',
            'file_uri' => '',
            'file_path' => '',

        );
        //$sync_parameter['advanced_sync'] = true;

        $sync_parameter = array_merge($sync_parameter, $parameter);

        return parent::sync($sync_parameter);
    }
}