<?php
// Class Object
// Name: view_product
// Description: entity_product's main view table

class view_product extends view
{
    var $parameter = array(
        'entity' => 'entity_product',
        'table' => 'tbl_view_product',
        'primary_key' => 'id'
    );

    function __construct($value = Null, $parameter = array())
    {
        if (!isset($parameter['page_size'])) $this->parameter['page_size'] = $GLOBALS['global_preference']->view_product_page_size;

        parent::__construct($value, $parameter);

        return $this;
    }
}
    
?>