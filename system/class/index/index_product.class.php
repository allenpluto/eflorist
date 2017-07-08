<?php
// Class Object
// Name: index_product
// Description: organization's main index table, include all possible search fields

class index_product extends index
{
    function __construct($value = Null, $parameter = array())
    {
        parent::__construct($value, $parameter);

        return $this;
    }

    // Exact Match Search
    function filter_by_category($parameter = array())
    {
        if (empty($parameter['category_id']))
        {
            return array();
        }
        $category_id_group = $this->format->id_group($parameter['category_id']);
        if (empty($category_id_group))
        {
            $this->message->notice = __FILE__.'(line '.__LINE__.'): '.get_class($this).' invalid organization id(s): '.print_r($parameter['category_id'],true);
            return array();
        }

        $filter_parameter = array(
            'where' => 'category_id IN ('.implode(',',array_keys($category_id_group)).')',
        );

        $filter_parameter = array_merge_recursive($filter_parameter, $parameter);
        if (!isset($filter_parameter['bind_param'])) $filter_parameter['bind_param'] = array();
        $filter_parameter['bind_param'] = array_merge($filter_parameter['bind_param'], $category_id_group);

        return parent::get($filter_parameter);
    }

    // Fuzzy Search
    function filter_by_keyword($value, $parameter = array())
    {
        $filter_parameter = array(
            'value'=> $value,
            'special_pattern'=>'\&\'',
            'fulltext_index_key'=>'fulltext_keyword'
        );
        $filter_parameter = array_merge($filter_parameter,$parameter);
        return $this->full_text_search($filter_parameter);
    }

}

?>