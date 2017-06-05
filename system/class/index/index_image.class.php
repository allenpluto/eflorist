<?php
// Class Object
// Name: index_image
// Description: image's main index table, include all possible search fields

class index_image extends index
{
    var $parameter = array(
        'table' => 'tbl_view_image'
    );

    function __construct($value = Null, $parameter = array())
    {
        parent::__construct($value, $parameter);

        return $this;
    }

    // Exact Match Search
    function filter_by_gallery($value, $parameter = array())
    {
        $format = format::get_obj();
        $gallery_id_group = $format->id_group(array('value'=>$value,'key_prefix'=>':gallery_id_'));
        if ($gallery_id_group === false)
        {
            $GLOBALS['global_message']->error = __FILE__.'(line '.__LINE__.'): '.get_class($this).' invalid gallery id(s)';
            return false;
        }

        $filter_parameter = array(
            'primary_key' => 'image_id',
            'table' => 'Gallery_Image',
            'where' => 'gallery_id IN ('.implode(',',array_keys($gallery_id_group)).')',
        );

        $filter_parameter = array_merge($filter_parameter, $parameter);
        if (!isset($filter_parameter['bind_param'])) $filter_parameter['bind_param'] = array();
        $filter_parameter['bind_param'] = array_merge($filter_parameter['bind_param'], $gallery_id_group);

        return parent::get($filter_parameter);
    }

    // Get Specific Fields
    function get_gallery_images($value, $parameter = array())
    {
        if (!isset($parameter['item_type']))
        {
            $parameter['item_type'] = 'listing';
        }
        $format = format::get_obj();
        $item_id_group = $format->id_group(array('value'=>$value,'key_prefix'=>':item_id_'));
        if ($item_id_group === false)
        {
            $GLOBALS['global_message']->error = __FILE__.'(line '.__LINE__.'): '.get_class($this).' invalid item id(s) (Please provide listing or category id to get corresponding galleries)';
            return false;
        }

        $filter_parameter = array(
            'primary_key' => 'image_id',
            'get_field' => array(
                'gallery_id' => 'Gallery.id',
                'gallery_name' => 'Gallery.title'
            ),
            'table' => 'Gallery_Image JOIN Gallery ON Gallery_Image.gallery_id = Gallery.id JOIN Gallery_Item ON Gallery_Image.gallery_id = Gallery_Item.gallery_id',
            'where' => 'item_id IN ('.implode(',',array_keys($item_id_group)).') AND item_type = :item_type',
        );
        $filter_parameter = array_merge($filter_parameter, $parameter);
        if (!isset($filter_parameter['bind_param'])) $filter_parameter['bind_param'] = array();
        $filter_parameter['bind_param'] = array_merge($filter_parameter['bind_param'], $item_id_group,array(':item_type'=>$parameter['item_type']));

        $result = parent::get($filter_parameter);
        if ($result === false) return false;
        else
        {
            $gallery_image = array();
            foreach ($this->id_group as $id_index=>$id)
            {
                if (!isset($gallery_image[$result['gallery_id'][$id]]))
                {
                    $gallery_image[$result['gallery_id'][$id]] = array(
                        'name'=>$result['gallery_name'][$id],
                        'image'=>array()
                    );
                }
                $gallery_image[$result['gallery_id'][$id]]['image'][] = $id;
            }
            return $gallery_image;
        }
    }
}

?>