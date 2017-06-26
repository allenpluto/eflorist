<?php
// Class Object
// Name: view_web_page
// Description: entity_web_page's main view table

class view_web_page extends view
{
    var $parameter = array(
        'entity' => 'entity_web_page',
        'table' => 'tbl_view_web_page',
        'primary_key' => 'id',
        'page_size' => 1
    );

    function __construct($value = Null, $parameter = array())
    {
        if (!isset($parameter['template']) AND isset($parameter['namespace']))
        {
            if (isset($parameter['instance']) AND file_exists(PATH_TEMPLATE.PREFIX_TEMPLATE_PAGE.$parameter['namespace'].'_'.$parameter['instance'].FILE_EXTENSION_TEMPLATE))
            {
                $this->parameter['template'] = PREFIX_TEMPLATE_PAGE.$parameter['namespace'].'_'.$parameter['instance'];
            }
            else if (file_exists(PATH_TEMPLATE.PREFIX_TEMPLATE_PAGE.$parameter['namespace'].FILE_EXTENSION_TEMPLATE))
            {
                $this->parameter['template'] = PREFIX_TEMPLATE_PAGE.$parameter['namespace'];
            }
            else
            {
                $this->parameter['template'] = PREFIX_TEMPLATE_PAGE.'default';
            }
        }

        parent::__construct($value, $parameter);

        return $this;
    }

    function fetch_value($parameter = array())
    {
        if (parent::fetch_value($parameter) === false)
        {
            return false;
        }
        foreach ($this->row as $row_index=>&$row_value)
        {
            $row_value['base'] = URI_SITE_BASE;
            if ($GLOBALS['global_preference']->environment != 'production')
            {
                $row_value['robots'] = 'noindex, nofollow';
            }
            else
            {
                if (!isset($row_value['robots'])) $row_value['robots'] = 'index, follow';
            }
            if (!empty($row_value['extra_field']))
            {
                $extra_field = json_decode($row_value['extra_field'],true);
                if (is_array($extra_field))
                {
                    $row_value = array_merge($row_value,$extra_field);
                }
            }
        }
        return $this->row;
    }
}
    
?>