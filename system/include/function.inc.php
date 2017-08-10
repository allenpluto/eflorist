<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 26/09/2016
 * Time: 2:24 PM
 */
if (!isset($start_time)) $start_time = microtime(1);

function render_xml($field = array(), &$xml = NULL, $parent_node_name = '')
{
    if (!isset($xml)) $xml = new SimpleXMLElement('<?xml version="1.0"?><response></response>');
    foreach ($field as $field_name=>$field_value)
    {
        if (!empty($parent_node_name)) $field_name = $parent_node_name;
        if (is_array($field_value))
        {
            // For sequential array
            if (array_keys($field_value) === range(0, count($field_value) - 1))
            {
                $parent_node = $xml->addChild($field_name.'s');
                render_xml($field_value,$parent_node,$field_name);
            }
            else
            {
                $parent_node = $xml->addChild($field_name);
                render_xml($field_value,$parent_node);
            }
        }
        else
        {
            $xml->addChild($field_name,htmlspecialchars($field_value));
        }
    }
    return $xml;
}

function render_html($field = array())
{
    $global_field = &$GLOBALS['global_field'];
    $field_parameter = array();
    if (isset($field['_parameter']))
    {
        $field_parameter = $field['_parameter'];
        unset($field['_parameter']);
    }
    if (isset($field_parameter['template_name']))
    {
        $GLOBALS['time_stack']['analyse template '.$field_parameter['template_name']] = microtime(1) - $GLOBALS['start_time'];
        if (file_exists(PATH_TEMPLATE.$field_parameter['template_name'].FILE_EXTENSION_TEMPLATE))
        {
            $field_parameter['template'] = file_get_contents(PATH_TEMPLATE.$field_parameter['template_name'].FILE_EXTENSION_TEMPLATE);
        }
        else
        {
            $GLOBALS['global_message']->notice = 'rendering error: template ['.PATH_TEMPLATE.$field_parameter['template_name'].FILE_EXTENSION_TEMPLATE.'] file does not exist';
        }
    }
    if (!isset($field_parameter['template']))
    {
        $field_parameter['template'] = '[[*_placeholder]]';
    }
    if (isset($field_parameter['container_name']))
    {
        if (file_exists(PATH_TEMPLATE.$field_parameter['container_name'].FILE_EXTENSION_TEMPLATE))
        {
            $field_parameter['container'] = file_get_contents(PATH_TEMPLATE.$field_parameter['container_name'].FILE_EXTENSION_TEMPLATE);
        }
        else
        {
            $GLOBALS['global_message']->warning = 'render_html error: container ['.PATH_TEMPLATE.$field_parameter['container_name'].FILE_EXTENSION_TEMPLATE.'] file does not exist';
            $field_parameter['container'] = '[[*_placeholder]]';
        }
    }
    if (isset($field_parameter['empty_template_name']))
    {
        if (file_exists(PATH_TEMPLATE.$field_parameter['empty_template_name'].FILE_EXTENSION_TEMPLATE))
        {
            $field_parameter['empty_template'] = file_get_contents(PATH_TEMPLATE.$field_parameter['empty_template_name'].FILE_EXTENSION_TEMPLATE);
        }
        else
        {
            $GLOBALS['global_message']->warning = 'render_html error: empty_template ['.PATH_TEMPLATE.$field_parameter['empty_template_name'].FILE_EXTENSION_TEMPLATE.'] file does not exist';
            $field_parameter['empty_template'] = '';
        }
    }
    if (!isset($field_parameter['separator']))
    {
        $field_parameter['separator'] = '';
    }
    if (isset($field['_value']))
    {
        $field = $field['_value'];
    }
//if ($field_parameter['template_name'] == 'view_manager_web_page')
//{
//    echo 'test point 1';
//    print_r($field);
//    print_r($field_parameter);
//}
    if (empty($field))
    {
        if (!empty($field_parameter['empty_template']))
        {
            $sub_field = array();
            if (!empty($field_parameter['parent_row'])) $sub_field = $field_parameter['parent_row'];
            $field_rendered_content = render_html(array('_value'=>$sub_field,'_parameter'=>array('template'=>$field_parameter['empty_template'])));
        }
        else
        {
            $field_rendered_content = '';
        }
    }
    else
    {
        if (!is_array($field))
        {
            $field = array('_placeholder'=>$field);
        }
        if (!isset($field[0]))
        {
            $field = array($field);
        }

        $template_match = array();
        $rendered_content = array();
        foreach($field as $field_row_index=>&$field_row)
        {
            if (!is_array($field_row))
            {
                $field_row = array('_placeholder'=>$field_row);
                $field_row_parameter = $field_parameter;
            }
            else
            {
                if (isset($field_row['_parameter']))
                {
                    $field_row_parameter = array_merge($field_parameter,$field_row['_parameter']);
                    unset($field_row['_parameter']);
                }
                else
                {
                    $field_row_parameter = $field_parameter;
                }

                if (!isset($field_row_parameter['template_name']))
                {
                    if (empty($field_row_parameter['template'])) continue;
                    $template_counter = 0;
                    $field_row_parameter['template_name'] = hash('crc32b',$template_counter.$field_row_parameter['template']);
                    while (isset($template_match[$field_row_parameter['template_name']]))
                    {
                        if ($template_match[$field_row_parameter['template_name']]['template'] == $field_row_parameter['template'])
                        {
                            break;
                        }
                        $template_counter++;
                        $field_row_parameter['template_name'] = hash('crc32b',$template_counter.$field_row_parameter['template']);
                    }
                    if (!isset($template_match[$field_row_parameter['template_name']]))
                    {
                        $template_match[$field_row_parameter['template_name']] = array('template'=>$field_row_parameter['template']);
                    }
                }
                else
                {
                    if (!isset($field_parameter['template_name']) OR $field_row_parameter['template_name'] != $field_parameter['template_name'])
                    {
                        if (file_exists(PATH_TEMPLATE.$field_row_parameter['template_name'].FILE_EXTENSION_TEMPLATE))
                        {
                            $field_row_parameter['template'] = file_get_contents(PATH_TEMPLATE.$field_row_parameter['template_name'].FILE_EXTENSION_TEMPLATE);
                        }
                        else
                        {
                            $GLOBALS['global_message']->notice = 'render_html error: template ['.PATH_TEMPLATE.$field_row_parameter['template_name'].FILE_EXTENSION_TEMPLATE.'] file does not exist';
                            $field_row_parameter['template'] = '[[*_placeholder]]';
                        }
                    }
                }
                if (isset($field_row_parameter['container_name']) AND (!isset($field_parameter['container_name']) OR $field_row_parameter['container_name'] != $field_parameter['container_name']))
                {
                    if (file_exists(PATH_TEMPLATE.$field_row_parameter['container_name'].FILE_EXTENSION_TEMPLATE))
                    {
                        $field_row_parameter['container'] = file_get_contents(PATH_TEMPLATE.$field_row_parameter['container_name'].FILE_EXTENSION_TEMPLATE);
                    }
                    else
                    {
                        $GLOBALS['global_message']->warning = 'rendering error: container ['.PATH_TEMPLATE.$field_row_parameter['container_name'].FILE_EXTENSION_TEMPLATE.'] file does not exist';
                        $field_row_parameter['container'] = '[[*_placeholder]]';
                    }
                }
                if (isset($field_row_parameter['empty_template_name']) AND (!isset($field_parameter['empty_template_name']) OR $field_row_parameter['empty_template_name'] != $field_parameter['empty_template_name']))
                {
                    if (file_exists(PATH_TEMPLATE.$field_parameter['empty_template_name'].FILE_EXTENSION_TEMPLATE))
                    {
                        $field_row_parameter['empty_template'] = file_get_contents(PATH_TEMPLATE.$field_row_parameter['empty_template_name'].FILE_EXTENSION_TEMPLATE);
                    }
                    else
                    {
                        $GLOBALS['global_message']->warning = 'render_html error: empty_template ['.PATH_TEMPLATE.$field_row_parameter['empty_template_name'].FILE_EXTENSION_TEMPLATE.'] file does not exist';
                        $field_row_parameter['empty_template'] = '';
                    }
                }
            }
//if ($field_parameter['template_name'] == 'view_manager_web_page')
//{
//    echo 'test point 2';
//    print_r($field_row);
//}
            if (isset($field_parameter['parent_row']))
            {
                $field_row = array_merge($field_parameter['parent_row'],$field_row);
                unset($field_row_parameter['parent_row']);
            }

            if (!is_array($global_field)) print_r($global_field);
            if (!is_array($field_row)) print_r($field_row);
            $field_row = array_merge($global_field,$field_row);
//if ($field_parameter['template_name'] == 'view_manager_web_page')
//{
//    echo 'test point 3';
//    print_r($field_row);
//}
            $match_result = array();
            if (!isset($template_match[$field_row_parameter['template_name']]['match_result']))
            {
                $template_match[$field_row_parameter['template_name']]['match_result'] = array();
                $template_match[$field_row_parameter['template_name']]['template_translated'] = $field_row_parameter['template'];
                // If there are multi layer template variables, chunks..., set match_result array from inner ones to outer ones, due to regular express limitation
                while(preg_match_all('/\[\[((\W*)([_a-z][^\[]+?))\]\]/', $template_match[$field_row_parameter['template_name']]['template_translated'], $matches))
                {
                    $template_translate = array();
                    foreach($matches[3] as $match_key=>$match_value)
                    {
                        $current_item = '{{'.sha1($matches[1][$match_key]).'}}';
                        if (!isset($match_result[$current_item]))
                        {
                            $match_value_array = explode(':',$match_value);
                            $match_result[$current_item] = array('type'=>$matches[2][$match_key],'name'=>$match_value_array[0],'raw_code'=>$matches[0][$match_key],'parameter'=>array());
                            unset($match_value_array);
                            $template_translate[$matches[0][$match_key]] = $current_item;

                            if (preg_match_all('/:(\w+?)=`(.*?)`/', $match_value, $match_items))
                            {
                                foreach ($match_items[2] as $match_item_index=>$match_item_value)
                                {
                                    $match_result[$current_item]['parameter'][$match_items[1][$match_item_index]] = $match_item_value;
                                }
                            }
                        }
                    }

                    // If higher layer template variable decoded, put them on top of match_result array
                    $template_match[$field_row_parameter['template_name']]['match_result'] = array_merge($match_result,$template_match[$field_row_parameter['template_name']]['match_result']);

                    unset($matches);
                    unset($match_result);

                    // For template variables already decoded, change their code from [[template_variable]] to {{template_variable}}, then loop, so it can decode outer layer tv without conflict
                    $template_match[$field_row_parameter['template_name']]['template_translated'] = strtr($template_match[$field_row_parameter['template_name']]['template_translated'],$template_translate);
                }
            }
            else
            {
                $field_row_parameter['template'] = $template_match[$field_row_parameter['template_name']]['template_translated'];
            }

            $field_row_rendered_content = $template_match[$field_row_parameter['template_name']]['template_translated'];
            $translate_array = array();
            $match_result = $template_match[$field_row_parameter['template_name']]['match_result'];

            preg_match_all('/\{\{(?:.*?)\}\}/',$template_match[$field_row_parameter['template_name']]['template_translated'],$translated_matches);

            foreach($translated_matches[0] as $match_result_key)
            {
                $match_result_value = &$match_result[$match_result_key];
                //$match_result_value = array_merge($match_result_value,$field_row_parameter);
//                if (isset($match_result_value['condition']))
//                {
//                    if (empty($match_result_value['condition']))
//                    {
//                        $match_result_value['value'] = '';
//                    }
//                    else
//                    {
//                        if (isset($field_row[$match_result_value['condition']]) AND empty($field_row[$match_result_value['condition']]))
//                        {
//                            $match_result_value['value'] = '';
//                        }
//                    }
//                }
//                if (isset($match_result_value['field_name']))
//                {
//                    if (preg_match_all('/{{(?:.*?)}}/',$match_result_value['field_name'],$field_decoded_matches))
//                    {
//                        $sub_translate_array = array();
//                        foreach ($field_decoded_matches[0] as $sub_match_result_key_index=>$sub_match_result_key)
//                        {
//                            $sub_translate_array[$sub_match_result_key] = $match_result[$sub_match_result_key]['raw_code'];
//                        }
//                        $match_result_value['field_name'] = strtr($match_result_value['field_name'],$sub_translate_array);
//                        $match_result_value['field_name'] = render_html(['_value'=>$field_row,'_parameter'=>['template'=>$match_result_value['field_name']]]);
//                    }
//                }

                if (isset($match_result_value['parameter']['field']))
                {
                    $field_decoded = $match_result_value['parameter']['field'];
                    $field_decoded = json_decode($field_decoded,true);
                    if (is_array($field_decoded))
                    {
                        if (preg_match('/{{(.*?)}}/',$match_result_value['parameter']['field']))
                        {
                            foreach($field_decoded as $field_name=>&$field_value)
                            {
                                if (is_string($field_value) AND preg_match_all('/{{(?:.*?)}}/',$field_value,$field_decoded_matches))
                                {
                                    $sub_translate_array = array();
                                    foreach ($field_decoded_matches[0] as $sub_match_result_key_index=>$sub_match_result_key)
                                    {
                                        $sub_translate_array[$sub_match_result_key] = $match_result[$sub_match_result_key]['raw_code'];
                                    }
                                    $field_value = strtr($field_value,$sub_translate_array);
                                    $field_value = render_html(array('_value'=>$field_row,'_parameter'=>array('template'=>$field_value)));
                                }
                            }
                        }
                        $match_result_value['parameter']['parent_row'] = $field_decoded;
                        unset($match_result_value['parameter']['field']);
                    }
                    else
                    {
                        $GLOBALS['global_message']->warning = 'rendering error: object '.$match_result_value['name'].' field '.$match_result_value['parameter']['field'].' is not proper json_encode format';
                    }
                }
                switch($match_result_value['type'])
                {
                    case '*':
                        // Field value, directly set value from given field
                        if (isset($field_row[$match_result_value['name']]))
                        {
                            if (empty($field_row[$match_result_value['name']]))
                            {
                                if (empty($match_result_value['parameter']['empty_template']) AND empty($match_result_value['parameter']['empty_template_name']))
                                {
                                    $match_result_value['value'] = '';
                                    break;
                                }
                                else
                                {
                                    $sub_field = array(
                                        '_value'=>array(),
                                        '_parameter'=>$match_result_value['parameter']
                                    );
                                    if (!isset($sub_field['_parameter']['parent_row'])) $sub_field['_parameter']['parent_row'] = $field_row;
                                    else $sub_field['_parameter']['parent_row'] = array_merge($field_row,$sub_field['_parameter']['parent_row']);
                                    $match_result_value['value'] = render_html($sub_field);
                                    break;
                                }
                            }
                            else
                            {
                                // either array or string may apply to container
                                if (is_array($field_row[$match_result_value['name']]))
                                {
                                    $sub_field = array(
                                        '_value'=>$field_row[$match_result_value['name']],
                                        '_parameter'=>$match_result_value['parameter']
                                    );
                                    if (isset($sub_field['_value']['_parameter']))
                                    {
                                        $sub_field['_parameter'] = array_merge_recursive($sub_field['_value']['_parameter'],$sub_field['_parameter']);
                                        unset($sub_field['_value']['_parameter']);
                                    }
                                    if (isset($sub_field['_value']['_value']))
                                    {
                                        $sub_field['_value'] = $sub_field['_value']['_value'];
                                    }
                                    if (!isset($sub_field['_parameter']['parent_row'])) $sub_field['_parameter']['parent_row'] = $field_row;
                                    else $sub_field['_parameter']['parent_row'] = array_merge($field_row,$sub_field['_parameter']['parent_row']);
                                    if (!isset($sub_field['_parameter']['template']) AND !isset($sub_field['_parameter']['template_name']))
                                    {
                                        // If template not provided, try to guess template name from parent template name
                                        $sub_field['_parameter']['template_name'] = $field_row_parameter['template_name'].'_'.$match_result_value['name'];
                                    }
                                    $match_result_value['value'] = render_html($sub_field);
//
//
//                                    if (!isset($field_row[$match_result_value['name']]['_parameter']))
//                                    {
//                                        $field_row[$match_result_value['name']]['_parameter'] = array();
//                                    }
//                                    if (!isset($field_row[$match_result_value['name']]['_parameter']['parent_row']))
//                                    {
//                                        $field_row[$match_result_value['name']]['_parameter']['parent_row'] = $field_row;
//                                    }
//                                    $field_row[$match_result_value['name']]['_parameter'] = array_merge_recursive($match_result_value['parameter'],$field_row[$match_result_value['name']]['_parameter']);
//
//                                    if (!isset($field_row[$match_result_value['name']]['_parameter']['template']) AND !isset($field_row[$match_result_value['name']]['_parameter']['template_name']))
//                                    {
//                                        // If template not provided, try to guess template name from parent template name
//                                        $field_row[$match_result_value['name']]['_parameter']['template_name'] = $field_row_parameter['template_name'].'_'.$match_result_value['name'];
//                                    }
//                                    unset($field_row[$match_result_value['name']]['_parameter']['parent_row'][$match_result_value['name']]);
//
//                                    $match_result_value['value'] = render_html($field_row[$match_result_value['name']]);
                                }
                                else
                                {
//                                    $match_result_value['value'] = $field_row[$match_result_value['name']];
                                    if (empty($match_result_value['parameter']))
                                    {
                                        $match_result_value['value'] = $field_row[$match_result_value['name']];
                                    }
                                    else
                                    {
                                        $sub_field = array(
                                            '_value'=>$field_row[$match_result_value['name']],
                                            '_parameter'=>$match_result_value['parameter']
                                        );
                                        if (!isset($sub_field['_parameter']['parent_row'])) $sub_field['_parameter']['parent_row'] = $field_row;
                                        else $sub_field['_parameter']['parent_row'] = array_merge($field_row,$sub_field['_parameter']['parent_row']);
                                        $match_result_value['value'] = render_html($sub_field);
                                    }
                                }

                            }
                        }
                        else $match_result_value['value'] = '';
                        break;
                    case '$':
                        // Chunk, load sub-template
//                        if (!isset($match_result_value['parameter']['condition'])) $match_result_value['parameter']['condition'] = true;
//                        else $match_result_value['parameter']['condition'] = !empty($field_row[$match_result_value['parameter']['condition']]);
//                        if (!isset($match_result_value['parameter']['alternative_chunk'])) $match_result_value['parameter']['alternative_chunk'] = '';
//                        if (isset($match_result_value['parameter']['parent_row'])) $field_row = array_merge($field_row, $match_result_value['parameter']['parent_row']);
//                        if ($match_result_value['parameter']['condition']) $match_result_value['value'] = render_html(['_value'=>$field_row,'_parameter'=>['template_name'=>$match_result_value['name']]]);
//                        else $match_result_value['value'] = render_html(['_value'=>$field_row,'_parameter'=>['template_name'=>$match_result_value['parameter']['alternative_chunk']]]);
                        $sub_field = array(
                            '_value'=>$field_row,
                            '_parameter'=>$match_result_value['parameter']
                        );
                        $sub_field['_parameter']['template_name'] = $match_result_value['name'];
                        $match_result_value['value'] = render_html($sub_field);
                        break;
                    case '~':
                        $match_result_value['parameter']['output'] = $match_result_value['name'];
                        if (isset($match_result_value['parameter']['parent_row']))
                        {
                            $match_result_value['value'] = $match_result_value['parameter']['parent_row'];
                        }
                        else
                        {
                            $GLOBALS['global_message']->warning = 'rendering error: output '.$match_result_value['name'].' field is not set';
                        }
                        break;
                    case '':
                        // Object, fetch value and render for each row
                        if (empty($field_row[$match_result_value['name']]))
                        {
//print_r($field_row[$match_result_value['name']]);
//print_r($match_result_value['parameter']['empty_template']);
//print_r($match_result_value['parameter']['empty_template_name']);
                            if (empty($match_result_value['parameter']['empty_template']) AND empty($match_result_value['parameter']['empty_template_name']))
                            {
                                $match_result_value['value'] = '';
                                break;
                            }
                            $field_row[$match_result_value['name']] = array();
                        }

                        if (!isset($match_result_value['parameter']['object']))
                        {
                            $match_result_value['parameter']['object'] = 'view_'.$match_result_value['name'];
                        }
                        if (!isset($match_result_value['parameter']['template_name']))
                        {
                            $match_result_value['parameter']['template_name'] = $field_row_parameter['template_name'].'_'.$match_result_value['name'];
                            if (!file_exists(PATH_TEMPLATE.$match_result_value['parameter']['template_name'].FILE_EXTENSION_TEMPLATE)) $match_result_value['parameter']['template_name'] = $match_result_value['parameter']['object'];
                        }

                        if (!file_exists(PATH_TEMPLATE.$match_result_value['parameter']['template_name'].FILE_EXTENSION_TEMPLATE))
                        {
                            $match_result_value['value'] = '';
                            break;
                        }
                        $GLOBALS['time_stack']['analyse parameter for object '.$match_result_value['parameter']['object']] = microtime(1) - $GLOBALS['start_time'];

                        $field_row_value = $field_row[$match_result_value['name']];
                        if (!is_array($field_row_value))
                        {
                            $field_row_value = explode(',',$field_row_value);
                        }
                        else
                        {
                            $field_row_value = array_values($field_row_value);
                        }
                        if (isset($match_result_value['parameter']['page_size']))
                        {
                            // If page_size is set in object variable, crop the id_group before initial the object to avoid too many advanced sync slow down site load (may causing php execution timeout break if too many image files need to be downloaded)
                            $match_result_value['parameter']['page_size'] = intval($match_result_value['parameter']['page_size']);
                            if (isset($match_result_value['parameter']['page_number']))
                            {
                                if ($match_result_value['parameter']['page_number'] == 'random')
                                {
                                    $match_result_value['parameter']['page_number'] = rand(0, ceil(count($field_row_value)/$match_result_value['parameter']['page_size'])-1);
                                }
                                else
                                {
                                    $match_result_value['parameter']['page_number'] = intval($match_result_value['parameter']['page_number']);
                                    if ($match_result_value['parameter']['page_number'] < 0) $match_result_value['parameter']['page_number'] = 0;
                                    if ($match_result_value['parameter']['page_number'] > ceil(count($field_row_value)/$match_result_value['parameter']['page_size'])-1) $match_result_value['parameter']['page_number'] = ceil(count($field_row_value)/$match_result_value['parameter']['page_size'])-1;
                                }
                            }
                            else
                            {
                                $match_result_value['parameter']['page_number'] = 0;
                            }
                            if (count($field_row_value) > $match_result_value['parameter']['page_size'])
                            {
                                $sub_field_row_value = array();
                                $field_row_value_index = $match_result_value['parameter']['page_size']*$match_result_value['parameter']['page_number'];
                                while (isset($field_row_value[$field_row_value_index]))
                                {
                                    $sub_field_row_value[] = $field_row_value[$field_row_value_index];
                                    $field_row_value_index++;
                                    if ($field_row_value_index >= $match_result_value['parameter']['page_size']*($match_result_value['parameter']['page_number']+1)) break;
                                }
//echo "\ntest point 1\n".$match_result_value['page_number']."\n".$field_row_value_index."\n";print_r($field_row_value);print_r($sub_field_row_value);exit;
                                $field_row_value = $sub_field_row_value;
                                unset($sub_field_row_value);
                            }
                        }

                        try
                        {
                            $object = new $match_result_value['parameter']['object']($field_row_value);
                            $GLOBALS['time_stack']['create object 1 '.$match_result_value['parameter']['object']] = microtime(1) - $GLOBALS['start_time'];
                        }
                        catch (Exception $e)
                        {
                            // Error Handling, error rendering sub object during render_html
                            $GLOBALS['global_message']->error = 'error rendering sub object during render_html'.$e->getMessage();
                            $match_result_value['value'] = $e->getMessage();
                            break;
                        }
                        $GLOBALS['time_stack']['create object '.$match_result_value['parameter']['object']] = microtime(1) - $GLOBALS['start_time'];

                        $result = $object->fetch_value();
if ($match_result_value['parameter']['object'] == 'view_web_page')
{
    echo "\ntest point 3\n";
    print_r($field_row[$match_result_value['name']]);
    print_r($field_row_value);
    print_r($result);
    print_r($match_result_value);
    print_r($object);
}
                        $GLOBALS['time_stack']['fetch value '.$match_result_value['parameter']['object']] = microtime(1) - $GLOBALS['start_time'];
                        unset($object);
                        if (empty($result))
                        {
                            if (!empty($match_result_value['parameter']['empty_template_name']) OR !empty($match_result_value['parameter']['empty_template']))
                            {
                                $empty_field_row = $field_row;
                                $empty_field_parameter = array();
                                if (isset($field_parameter['parent_row'])) $empty_field_row = array_merge($empty_field_row,$field_parameter['parent_row']);
                                if (isset($match_result_value['parameter']['parent_row'])) $empty_field_row = array_merge($empty_field_row,$match_result_value['parameter']['parent_row']);
                                if (!empty($match_result_value['parameter']['empty_template'])) $empty_field_parameter['template'] = $match_result_value['parameter']['empty_template'];
                                else $empty_field_parameter['template_name'] = $match_result_value['parameter']['empty_template_name'];
//print_r($field_parameter);
//print_r(['_value'=>$empty_field_row,'_parameter'=>$empty_field_parameter]);

                                $match_result_value['value'] = render_html(array('_value'=>$empty_field_row,'_parameter'=>$empty_field_parameter));
                                unset($empty_field_row);
                            }
                            else
                            {
                                $match_result_value['value'] = '';
                            }
                        }
                        else
                        {
                            //$field_row[$match_result_value['name']]['_parameter'] = array_merge_recursive($match_result_value['parameter'],$field_row[$match_result_value['name']]['_parameter']);

                            $sub_field = array(
                                '_value'=>array_values($result),
                                '_parameter'=>$match_result_value['parameter']
                            );
                            if (!isset( $sub_field['_parameter']['parent_row'])) $sub_field['_parameter']['parent_row'] = array();
                            $sub_field['_parameter']['parent_row'] = array_merge($field_row, $sub_field['_parameter']['parent_row']);
                            $match_result_value['value'] = render_html($sub_field);

//                            $sub_field = array_values($result);
//                            unset($result);
//
//                            $sub_field_parent_row = $field_row;
//                            if (isset($match_result_value['parameter']['parent_row']))
//                            {
//                                $sub_field_parent_row = array_merge($sub_field_parent_row, $match_result_value['parameter']['parent_row']);
//                            }
//                            $match_result_value['value'] = render_html(['_value'=>$sub_field,'_parameter'=>['parent_row'=>$sub_field_parent_row,'template_name'=>$match_result_value['parameter']['template_name']]]);
//                            unset($sub_field_parent_row);
                        }
                        break;
                    case '-':
                        $match_result_value['value'] = '';
                        break;
                    case '+':
                        // do not replace, keep for further operation, such as insert style or script
                        $match_result_value['value'] = $match_result_value['raw_code'];
                        break;
                    default:
                        $match_result_value['value'] = '';
                }
                if (isset($match_result_value['value']))
                {
//                    if (isset($match_result_value['parameter']['container_name']))
//                    {
//                        if (file_exists(PATH_TEMPLATE.$match_result_value['parameter']['container_name'].FILE_EXTENSION_TEMPLATE))
//                        {
//                            $match_result_value['parameter']['container'] = file_get_contents(PATH_TEMPLATE.$match_result_value['parameter']['container_name'].FILE_EXTENSION_TEMPLATE);
//                        }
//                        else {
//                            $GLOBALS['global_message']->warning = 'render_html error: container ['.PATH_TEMPLATE.$match_result_value['parameter']['container_name'].FILE_EXTENSION_TEMPLATE.'] file does not exist';
//                            $match_result_value['parameter']['container'] = '[[*_placeholder]]';
//                        }
//                    }
//                    if (isset($match_result_value['parameter']['container']))
//                    {
//                        $match_result_value['value'] = str_replace('[[*_placeholder]]',$match_result_value['value'],$match_result_value['parameter']['container']);
//                    }
                    if (empty($match_result_value['parameter']['output']))
                    {
                        $translate_array[$match_result_key] = $match_result_value['value'];
                    }
                    else
                    {
                        if (!isset($global_field[$match_result_value['parameter']['output']])) $global_field[$match_result_value['parameter']['output']] = array();
                        $global_field[$match_result_value['parameter']['output']][sha1(json_encode($match_result_value['value']))] = $match_result_value['value'];
                        $translate_array[$match_result_key] = '';
                    }
                }
                else
                {
                    $translate_array[$match_result_key] = $match_result_value['raw_code'];
                }
                $GLOBALS['time_stack']['render variable '.$match_result_key] = microtime(1) - $GLOBALS['start_time'];
            }

            $field_row_rendered_content = strtr($field_row_rendered_content,$translate_array);

            while (preg_match_all('/\{\{(.*?)\}\}/',$field_row_rendered_content,$rendered_matches))
            {
                $rendered_translate_array = array();
                foreach($rendered_matches[0] as $match_result_key)
                {
                    if (isset($match_result[$match_result_key]['raw_code']))
                    {
                        $rendered_translate_array[$match_result_key] = $match_result[$match_result_key]['raw_code'];
                    }
                    else
                    {
                        $rendered_translate_array[$match_result_key] = '';
                        $GLOBALS['global_message']->warning = $match_result_key.' has no matched raw_code';
                    }

                }
                $field_row_rendered_content = strtr($field_row_rendered_content,$rendered_translate_array);
            }

            // self loop, if page still have untranslated template variables (place holder type excepted, [[+example]], as they are not suppose to be translated at all), use same field and template to render again
            if (preg_match('/\[\[[^\+][a-z](.*?)\]\]/', $field_row_rendered_content))
            {
                $field_row_rendered_content = render_html(array('_value'=>$field_row,'_parameter'=>array('template'=>$field_row_rendered_content,'debug'=>true)));
            }

//            if (!empty($field_row_parameter['container']) AND !empty($field_row_rendered_content))
//            {
//                $field_row_rendered_content = str_replace('[[*_placeholder]]',$field_row_rendered_content,$field_row_parameter['container']);
//            }

            $rendered_content[] = $field_row_rendered_content;
        }

        $field_rendered_content = implode($field_parameter['separator'],$rendered_content);
    }

    if (!empty($field_parameter['container']) AND !empty($field_rendered_content))
    {
        $field_rendered_content = str_replace('[[*_placeholder]]',$field_rendered_content,$field_parameter['container']);
    }

    return $field_rendered_content;

}

function minify_content($value, $type='html')
{
    if (empty($value))
    {
        return '';
    }

    switch($type)
    {
        case 'css':
            // Minify CSS

            // Remove comments
            $search = array(
                '/\/\*(.*?)\*\//s'                  // remove css comments
            );
            $replace = array(
                ''
            );
            $value = preg_replace($search, $replace, $value);

            // Preserve Quoted Content
            $counter = 0;
            $quoted_content = array();
            while(preg_match('/"((?:[^"]|\\")*?)(?<!\\\)"/',$value,$matches,PREG_OFFSET_CAPTURE))
            {
                $quoted_content['[[*quoted_content_'.$counter.']]'] = $matches[0][0];
                $value = substr_replace($value,'[[*quoted_content_'.$counter.']]',$matches[0][1],strlen($matches[0][0]));
                $counter++;
            }
            while(preg_match('/\'((?:[^\']|\\\')*?)(?<!\\\)\'/',$value,$matches,PREG_OFFSET_CAPTURE))
            {
                $quoted_content['[[*quoted_content_'.$counter.']]'] = $matches[0][0];
                $value = substr_replace($value,'[[*quoted_content_'.$counter.']]',$matches[0][1],strlen($matches[0][0]));
                $counter++;
            }
            unset($counter);

            // Minify Content
            $search = array(
                '/([,:;\{\}])[^\S]+/',             // strip whitespaces after , : ; { }
                '/[^\S]+([,:;\{\}])/',             // strip whitespaces before , : ; { }
                '/(\s)+/'                            // shorten multiple whitespace sequences
            );
            $replace = array(
                '\\1',
                '\\1',
                '\\1'
            );
            $value = preg_replace($search, $replace, $value);

            // Restore Quoted Content
            for ($i=0;$i<2;$i++)  $value = strtr($value,$quoted_content);

            return $value;
        case 'html':
            // Minify HTML

            // Remove comments
            $search = array(
                '/<\!--(?!\[if)(.*?)-->/s'       // remove html comments, except IE comments
            );
            $replace = array(
                ''
            );
            $value = preg_replace($search, $replace, $value);

            // Preserve inline script and style
            $counter = 0;
            $quoted_content = array();
            while(preg_match('/\<script(.*?)\<\/script\>/',$value,$matches,PREG_OFFSET_CAPTURE))
            {
                $quoted_content['[[*quoted_content_'.$counter.']]'] = $matches[0][0];
                $value = substr_replace($value,'[[*quoted_content_'.$counter.']]',$matches[0][1],strlen($matches[0][0]));
                $counter++;
            }
            while(preg_match('/\<style(.*?)\<\/style\>/',$value,$matches,PREG_OFFSET_CAPTURE))
            {
                $quoted_content['[[*quoted_content_'.$counter.']]'] = $matches[0][0];
                $value = substr_replace($value,'[[*quoted_content_'.$counter.']]',$matches[0][1],strlen($matches[0][0]));
                $counter++;
            }
            unset($counter);

            // Minify Content
            $search = array(
                '/\>[^\S ]+/',                      // strip whitespaces after tags, except space
                '/[^\S ]+\</',                      // strip whitespaces before tags, except space
                '/(\s)+/'                            // shorten multiple whitespace sequences
            );
            $replace = array(
                '>',
                '<',
                '\\1'
            );
            $value = preg_replace($search, $replace, $value);

            // Restore Quoted Content
            for ($i=0;$i<2;$i++)  $value = strtr($value,$quoted_content);

            return $value;
        case 'js':
            // Minify JS

            // Remove comments
            $search = array(
                '/\/\*(.*?)\*\//s',                       // remove js comments with /* */
                '/\/\/(.*?)[\n\r]/s'                     // remove js comments with //
            );
            $replace = array(
                '',
                ''
            );
            $value = preg_replace($search, $replace, $value);

            // Preserve Quoted Content
            $counter = 0;
            $quoted_content = array();
            while(preg_match('/"(?:[^"]|\\")*?(?<!\\\)"/',$value,$matches,PREG_OFFSET_CAPTURE))
            {
                $quoted_content['[[*quoted_content_'.$counter.']]'] = $matches[0][0];
                $value = substr_replace($value,'[[*quoted_content_'.$counter.']]',$matches[0][1],strlen($matches[0][0]));
                $counter++;
            }
            while(preg_match('/\'(?:[^\']|\\\')*?(?<!\\\)\'/',$value,$matches,PREG_OFFSET_CAPTURE))
            {
                $quoted_content['[[*quoted_content_'.$counter.']]'] = $matches[0][0];
                $value = substr_replace($value,'[[*quoted_content_'.$counter.']]',$matches[0][1],strlen($matches[0][0]));
                $counter++;
            }
            while(preg_match('/(?<!\/)\/(?:[^\/\n\r]|\\\\\/)+?(?<!\\\)\//',$value,$matches,PREG_OFFSET_CAPTURE))
            {
                $quoted_content['[[*quoted_content_'.$counter.']]'] = $matches[0][0];
                $value = substr_replace($value,'[[*quoted_content_'.$counter.']]',$matches[0][1],strlen($matches[0][0]));
                $counter++;
            }
            unset($counter);
            unset($matches);

            // Minify Content
            $search = array(
                '/([\<\>\=\+\-,:;\(\)\{\}])[^\S]+/',        // strip whitespaces after , : ; { }
                '/[^\S]+([\<\>\=\+\-,:;\(\)\{\}])/',        // strip whitespaces before , : ; { }
                '/^(\s)+/'                                 // strip whitespaces in the start of the file
            );
            $replace = array(
                '\\1',
                '\\1',
                ''
            );
            $value = preg_replace($search, $replace, $value);

            // Restore Quoted Content
            for ($i=0;$i<3;$i++)  $value = strtr($value,$quoted_content);

            return $value;
        default:
            // Error Handling, minify unknown type
            $GLOBALS['global_message']->error = 'minify_content - unrecognized minify type '.$type;
            return false;
    }
}

function password_hashing($value)
{
    if (is_array($value))
    {
        extract($value);
    }
    if (isset($password))
    {
        $value = $password;
    }
    if (isset($name) AND !isset($salt))
    {
        $salt = substr(hash('sha256',$name),-5);
    }
    if (empty($value))
    {
        return False;
    }
    if (!isset($salt))
    {
        $salt = '';
    }
    $result = hash('sha256',hash('crc32b',$value.$salt));
    return $result;
}

function get_remote_ip()
{
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP']))
    {
        // For Cloud Flare forwarded request, get the original remote ip address
        return $_SERVER['HTTP_CF_CONNECTING_IP'];
    }
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
    {
        return $_SERVER['HTTP_CLIENT_IP'];
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
    {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    return $_SERVER['REMOTE_ADDR'];
}

// PHP compatible issue fix
if (!function_exists('mime_content_type'))
{
    function mime_content_type($filename)
    {
        $filename_parts = explode('.',$filename);
        $file_extension = end($filename_parts);
        $file_mime = '';
        switch($file_extension)
        {
            case 'css':
                $file_mime = 'text/css';
                break;
            case 'gif':
                $file_mime = 'image/gif';
                break;
            case 'jpg':
                $file_mime = 'image/jpeg';
                break;
            case 'js':
                $file_mime = 'application/javascript';
                break;
            case 'json':
                $file_mime = 'application/json';
                break;
            case 'png':
                $file_mime = 'image/png';
                break;
            case 'svg':
                $file_mime = 'image/svg+xml';
                break;
        }
        return $file_mime;
    }
}

if (!function_exists('http_response_code'))
{
    function http_response_code($status)
    {
        header(':', true, $status);
    }
}
