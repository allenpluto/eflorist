<?php
// Class Object
// Name: view_category_image
// Description: image view

class view_category_image extends view_image
{
    function fetch_value($parameter = array())
    {
        if (!isset($parameter['image_size'])) $parameter['image_size'] = 's';
        $result = parent::fetch_value($parameter);
        if ($result !== false AND is_array($this->row))
        {
            foreach ($this->row as $row_index=>$row_value)
            {
                $this->row[$row_index]['m_file_uri'] = URI_IMAGE . $row_value['friendly_uri'] . '-' . $row_value['id'] . '.m.' . $row_value['file_extension'];
                $this->row[$row_index]['l_file_uri'] = URI_IMAGE . $row_value['friendly_uri'] . '-' . $row_value['id'] . '.l.' . $row_value['file_extension'];
            }
            $result = $this->row;
        }
        return $result;
    }
}

?>