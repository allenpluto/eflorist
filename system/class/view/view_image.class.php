<?php
// Class Object
// Name: view_image
// Description: image view

class view_image extends view
{
    var $parameter = array(
        'entity' => 'entity_image',
        'table' => 'tbl_view_image',
        'primary_key' => 'id',
        'page_size' => 1
    );

    function __construct($value = Null, $parameter = array())
    {
        $this->parameter['page_size'] = $GLOBALS['global_preference']->view_category_page_size;

        parent::__construct($value, $parameter);

        return $this;
    }

    function fetch_value($parameter = array())
    {
        $parameter = array_merge($this->parameter,$parameter);
        $result = parent::fetch_value($parameter);
        if ($result == false) return $result;
        $sync_id_group = array();
        $image_width_array = array_keys($this->preference->image['width']);

        foreach ($result as $row_index=>&$row)
        {
            if (!file_exists($row['file_path']))
            {
                $this->message->warning = 'View Image local source file does not exist '.$row['file_path'];
                $sync_id_group[] = $row['id'];
            }
            foreach ($image_width_array as $image_width_index=>$image_width)
            {
                $row[$image_width.'_file_uri'] = URI_IMAGE . $row['friendly_uri'] . '-' . $row['id'] . '.' . $image_width . '.' . $row['file_extension'];
            }
        }
        if (!empty($sync_id_group))
        {
            $entity_obj = new $this->parameter['entity']();
            $entity_obj->sync(array('id_group'=>$sync_id_group,'sync_type'=>'update_current'));
            unset($entity_obj);
        }
        return $result;
    }
}
    
?>