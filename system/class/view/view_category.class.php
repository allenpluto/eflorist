<?php
// Class Object
// Name: view_category
// Description: entity_category's main view table

class view_category extends view
{
    var $parameter = array(
        'entity' => 'entity_category',
        'table' => 'tbl_view_category',
        'primary_key' => 'id'
    );

    function __construct($value = Null, $parameter = array())
    {
        if (!isset($parameter['page_size'])) $this->parameter['page_size'] = $GLOBALS['global_preference']->view_category_page_size;

        parent::__construct($value, $parameter);

        return $this;
    }
}
    
?>