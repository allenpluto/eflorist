<?php
// Include Class Object
// Name: content
// Description: web page content functions

// Render template, create html page view...

class content extends base {
    protected $request = array();
    protected $content = array();
    protected $result = array();

    function __construct($parameter = array())
    {
        parent::__construct();

        $this->request = array();
        $this->result = array(
            'status'=>200,
            'header'=>array(),
            'content'=>''
        );

        // Analyse uri structure and raw environment variables, store into $this->request
        if ($this->request_decoder($parameter) === false)
        {
            // Error Log, error during reading input uri and parameters
            $this->message->error = 'Fail: Error during request_decoder';
        }
        $this->time_stack['request_decoder'] = microtime(1);

        // Generate the necessary components for the content, store separate component parts into $content
        // Read data from database (if applicable), only generate raw data from db
        // If any further complicate process required, leave it to render
        if ($this->result['status'] == 200 AND $this->build_content() === false)
        {
            // Error Log, error during building data object
            $this->message->error = 'Fail: Error during build_content';
        }
        $this->time_stack['build_content'] = microtime(1);
//print_r('build_content: <br>');
//print_r($this);
//exit();
        // Processing file, database and etc (basically whatever time consuming, process it here)
        // As some rendering methods may only need the raw data without going through all the file copy, modify, generate processes
        if ($this->result['status'] == 200 AND $this->generate_rendering() === false)
        {
            // Error Log, error during rendering
            $this->message->error = 'Fail: Error during render';
        }
        $this->time_stack['generate_rendering'] = microtime(1);
//print_r('generate_rendering: <br>');
//print_r(filesize($this->content['target_file']['path']));
//print_r($this);
//exit();
    }

    private function request_decoder($value = '')
    {
        if (is_array($value))
        {
            if (!empty($value['value']))
            {
                extract($value);
            }
            else
            {
                $option = $value;
                $value = '';
            }
        }
        if (empty($value))
        {
            $value = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        }
        if (!isset($option)) $option = array();

        if (!empty($_GET))
        {
            $option = array_merge($option,$_GET);
            unset($_GET);
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' AND !empty($_POST))
        {
            // default post request with json format Input and Output
            $option = array_merge($option,$_POST);
            //if (!isset($option['data_type'])) $option['data_type'] = 'json';
            unset($_POST);
        }

        $option_preset = array('document','file_type','file_extension','file_extra_extension','module','template','action');
        foreach($option as $key=>$item)
        {
            // Options from GET, POST overwrite ones decoded from uri
            if (in_array($key,$option_preset))
            {
                $this->request[$key] = $item;
                unset($option[$key]);
            }
        }
        $option_unset = array('asset_redirect','request_uri','rewrite_base','final_request');
        foreach($option as $key=>$item)
        {
            // Options from GET, POST overwrite ones decoded from uri
            if (in_array($key,$option_unset))
            {
                unset($option[$key]);
            }
        }
        // dump the rest custom/unrecognized input variables into $request['option']
        $this->request['option'] = $option;
        unset($option_preset);
        unset($option);

        $request_uri = trim(preg_replace('/^[\/]?'.FOLDER_SITE_BASE.'[\/]/','',$value),'/');
        $request_path = explode('/',$request_uri);

        $type = array('css','font','image','js');
        $request_path_part = array_shift($request_path);
        if (in_array($request_path_part,$type))
        {
            $this->request['source_type'] = 'static_file';
            $this->request['file_type'] = $request_path_part;
            $this->request['file_uri'] = URI_ASSET.$this->request['file_type'].'/';
        }
        else
        {
            $type = array('xml','json');
            if (in_array($request_path_part,$type))
            {
                $this->request['source_type'] = 'data';
                $this->request['file_type'] = $request_path_part;
                $request_path_part = array_shift($request_path);
            }
            else
            {
                $this->request['source_type'] = 'data';
                if(!isset($this->request['file_type'])) $this->request['file_type'] = 'html';
            }
            if ($this->request['source_type'] == 'data')
            {
                $this->request['file_uri'] = URI_SITE_BASE;
            }
            else
            {
                $this->request['file_uri'] = URI_ASSET.$this->request['file_type'].'/';
            }
            if (!isset($this->request['file_extension'])) $this->request['file_extension'] = $this->request['file_type'];
        }
        $this->request['file_path'] = PATH_ASSET.$this->request['file_type'].DIRECTORY_SEPARATOR;
        if (file_exists(PATH_PREFERENCE.$this->request['file_type'].FILE_EXTENSION_INCLUDE))
        {
            include_once(PATH_PREFERENCE.$this->request['file_type'].FILE_EXTENSION_INCLUDE);
        }

        // HTML Page uri structure decoder
        switch ($this->request['source_type'])
        {
            case 'static_file':
                if (empty($request_path))
                {
                    // Folder forbid direct access
                    $this->result['status'] = 403;
                    return false;
                }

                $file_name = array_pop($request_path);
                $file_part = explode('.',$file_name);
                $this->request['document'] = array_shift($file_part);
                if (!empty($file_part)) $this->request['file_extension'] = array_pop($file_part);
                $this->request['file_extra_extension'] = array();

                if (is_array($this->preference->{$this->request['file_type']}))
                {
                    foreach ($this->preference->{$this->request['file_type']} as $file_option_name=>$file_option_value)
                    {
                        $file_option = array();
                        $file_option = array_keys($file_option_value);
                        foreach ($file_part as $file_extension_index=>$file_extension)
                        {
                            if (in_array($file_extension, $file_option)) {
                                $this->request['file_extra_extension'][$file_option_name] = $file_extension;
                                unset($file_part[$file_extension_index]);
                                break;
                            }
                        }
                        unset($file_option);
                    }
                }
                if (!empty($file_part))
                {
                    // Put the rest part that is not an extension back to document name, e.g. jquery-1.11.8.min.js
                    $this->request['document'] .= '.'.implode('.',$file_part);
                }
                unset($file_part);
                $decoded_file_name = $this->request['document'];
                if (!empty($this->request['file_extra_extension'])) $decoded_file_name .= '.'.implode('.',$this->request['file_extra_extension']);
                if (!empty($this->request['file_extension'])) $decoded_file_name .= '.'.$this->request['file_extension'];

                if ($file_name != $decoded_file_name)
                {
                    // Error Handling, decoded file name is not consistent to requested file name
                    $this->result['status'] = 404;
                    return false;
                }

                $this->request['sub_path'] = $request_path;

                if (!empty($this->request['sub_path']))
                {
                    $this->request['file_path'] .= implode(DIRECTORY_SEPARATOR,$this->request['sub_path']).DIRECTORY_SEPARATOR;
                    $this->request['file_uri'] .= implode('/',$this->request['sub_path']).'/';
                }
                $this->request['file_uri'] .= $file_name;

                if (preg_match('/-(\d*)$/',$this->request['document']))
                {
                    // images have special directory structure, images loaded from database real storage path is constructed by id
                    $file_name_parts = explode('-',$this->request['document']);
                    $file_id = array_pop($file_name_parts);
                    $this->request['file_id'] = $file_id;
                    $sub_folder = array();
                    do
                    {
                        $sub_image_id = $file_id % 1000;
                        array_unshift($sub_folder, $sub_image_id);
                        $file_id = floor($file_id / 1000);
                    } while ($file_id >= 1);
                    foreach ($sub_folder as $index=>&$sub_image_id)
                    {
                        if ($index != 0)
                        {
                            $sub_image_id = str_repeat('0', 3-strlen($sub_image_id)).$sub_image_id;
                        }
                    }
                    $this->request['file_path'] = $this->request['file_path'].implode(DIRECTORY_SEPARATOR,$sub_folder).DIRECTORY_SEPARATOR.$file_name;
                }
                else
                {
                    $this->request['file_path'] .= $file_name;
                }
                unset($file_name);
                break;
            case 'data':
            default:
                $control_panel = array('manager');
                if (in_array($request_path_part,$control_panel))
                {
                    $this->request['control_panel'] = $request_path_part;
                    $request_path_part = array_shift($request_path);
                }
                else
                {
                    $this->request['control_panel'] = '';
                }

                //$request_path_part = array_shift($request_path);
                $module = array('product');
                if (in_array($request_path_part,$module))
                {
                    $this->request['module'] = $request_path_part;
                    $request_path_part = array_shift($request_path);
                }
                else
                {
                    $this->request['module'] = '';
                }

                switch ($this->request['module'])
                {
                    case 'product':
                        if (!empty($request_path_part))
                        {
                            $this->request['category'] = $request_path_part;
                            $request_path_part = array_shift($request_path);
                            if (!empty($request_path_part))
                            {
                                $this->request['product'] = $request_path_part;
                            }
                        }
                    default:
                        if ($this->request['control_panel'] == '')
                        {
                            $this->request['document'] = $request_path_part;
                        }
                        else
                        {
                            $this->request['method'] = $request_path_part;
                        }
                }
                if (!isset($this->request['method']))
                {
                    $this->request['method'] = '';
                }
                if (!isset($this->request['action']))
                {
                    $this->request['action'] = '';
                }

                if (!empty($this->request['control_panel']))
                {
                    $this->request['file_uri'] .= $this->request['control_panel'].'/';
                }

                if (!empty($this->request['module']))
                {
                    $this->request['file_path'] .= $this->request['module'].DIRECTORY_SEPARATOR;
                    $this->request['file_uri'] .= $this->request['module'].'/';
                }

                if (!empty($this->request['method']))
                {
                    $this->request['file_path'] .= $this->request['method'].DIRECTORY_SEPARATOR;
                    $this->request['file_uri'] .= $this->request['method'].'/';
                }

//                if (!empty($this->request['action']))
//                {
//                    $this->request['file_path'] .= $this->request['action'].DIRECTORY_SEPARATOR;
//                    $this->request['file_uri'] .= $this->request['action'];
//                }
                if (!empty($this->request['category']))
                {
                    $this->request['file_path'] .= $this->request['category'].DIRECTORY_SEPARATOR;
                    $this->request['file_uri'] .= $this->request['category'];
                }

                if (!empty($this->request['document']))
                {
                    $this->request['file_path'] .= $this->request['document'].DIRECTORY_SEPARATOR;
                    $this->request['file_uri'] .= $this->request['document'];
                }

                $this->request['file_path'] .= 'index.'.$this->request['file_extension'];


                break;
        }

        $this->request['remote_ip'] = get_remote_ip();

        if (isset($_COOKIE['session_id']))
        {
            $this->request['session_id'] = $_COOKIE['session_id'];
        }

        if (isset($_SERVER['HTTP_AUTH_KEY']))
        {
            $this->request['auth_key'] = $_SERVER['HTTP_AUTH_KEY'];
        }

        if (isset($_SERVER['HTTP_REFERER']))
        {
            $this->request['http_referer'] = $_SERVER['HTTP_REFERER'];
        }

        if (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) != parse_url($this->request['file_uri'],PHP_URL_PATH))
        {
            if ($this->request['file_type'] == 'html')
            {
                $this->message->notice = 'Redirect - request uri ['.parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH).'] is different from decoded uri ['.parse_url($this->request['file_uri'],PHP_URL_PATH).']';
                $this->result['status'] = 301;
                $this->result['header']['Location'] =  $this->request['file_uri'].(!empty($this->request['option'])?('?'.http_build_query($this->request['option'])):'');
            }
        }
    }

    private function build_content()
    {
        // If session is set, try to get account from session
        if (!empty($this->request['session_id']))
        {
            $entity_account_session_obj = new entity_account_session();
            $method_variable = array('status'=>'OK','message'=>'','account_session_id'=>$this->request['session_id'],'remote_ip'=>$this->request['remote_ip']);

            $session = $entity_account_session_obj->validate_account_session_id($method_variable);
            if ($session == false)
            {
                // Error Handling, session validation failed, session_id invalid
                $this->message->notice = 'Session exists, but session validation failed';
            }
            else
            {
                $entity_account_obj = new entity_account($session['account_id']);
                if (empty($entity_account_obj->row))
                {
                    // Error Handling, session validation failed, session_id is valid, but cannot read corresponding account
                    $this->message->notice = 'Session Validation Succeed, but cannot find related account';
                }
                else
                {
                    $this->content['account'] = end($entity_account_obj->row);
                    $this->content['session'] = $session;
                }
                unset($entity_account_obj);
            }
            unset($session);
        }

        // If auth_key is set, try to get account from auth_key
        if (!empty($this->request['auth_key']))
        {
            $entity_account_key_obj = new entity_account_key();
            $method_variable = array('account_key'=>$this->request['auth_key'],'remote_ip'=>$this->request['remote_ip']);
            $auth_id = $entity_account_key_obj->validate_account_key($method_variable);
            if ($auth_id === false)
            {
                // Error Handling, Account key authentication failed
                $this->message->notice = 'Building: Account Key Authentication Failed';
                $this->content['api_result'] = array(
                    'status'=>$method_variable['status'],
                    'message'=>$method_variable['message']
                );
            }
            else
            {
                $entity_account_obj = new entity_account($this->content['account']['id']);
                if (empty($entity_account_obj->row))
                {
                    // Error Handling, session validation failed, account_key is valid, but cannot read corresponding account
                    $this->message->error = 'Account Key Authentication Succeed, but cannot find related account';
                    $this->content['account_result'] = array(
                        'status'=>'REQUEST_DENIED',
                        'message'=>'Cannot get account info, it might be suspended or temporarily inaccessible'
                    );
                }
                else
                {
                    $this->content['account'] = end($entity_account_obj->row);
                }
                unset($entity_account_obj);
            }
            unset($auth_id);
        }

        // Regularize Content output format
        if (!isset($this->request['option']['format'])) $this->content['format'] = $this->request['file_type'];
        else $this->content['format'] = $this->request['option']['format'];

        switch($this->request['source_type'])
        {
            case 'static_file':
                $this->content['target_file'] = array(
                    'path'=>$this->request['file_path'],
                    'uri'=>$this->request['file_uri']
                );

                if (file_exists($this->content['target_file']['path']))
                {
                    $this->content['target_file']['last_modified'] = filemtime($this->content['target_file']['path']);
                    $this->content['target_file']['content_length'] = filesize($this->content['target_file']['path']);
                }
                else
                {
                    $this->content['target_file']['last_modified'] = 0;
                    $this->content['target_file']['content_length'] = 0;
                }

//                $file_relative_path = $this->request['file_type'].DIRECTORY_SEPARATOR;
//                if (!empty($this->request['sub_path'])) $file_relative_path .= implode(DIRECTORY_SEPARATOR,$this->request['sub_path']).DIRECTORY_SEPARATOR;
//                $this->content['source_file'] = array(
//                    'path' => PATH_ASSET.$file_relative_path.$this->request['document'].'.src.'.$this->request['file_extension'],
//                    'source' => 'local_file'
//                );
//                $source_file_relative_path =  $file_relative_path .  $this->request['document'].'.src.'.$this->request['file_extension'];
//                $file_relative_path .= $this->request['document'].'.'.$this->request['file_extension'];

                $this->content['source_file'] = array(
                    'path' => dirname($this->request['file_path']).DIRECTORY_SEPARATOR.$this->request['document'].'.'.$this->request['file_extension'],
                    'source' => 'local_file'
                );

                if (isset($this->request['option']['source']))
                {
                    if (preg_match('/^http/',$this->request['option']['source']) == 1)
                    {
                        if (strpos($this->request['option']['source'],URI_SITE_BASE) == FALSE)
                        {
                            // If source_file is not relative uri and not start with current site uri base, it is an external (cross domain) source file
                            $this->content['source_file']['original_file'] = $this->request['option']['source'];
                            $this->content['source_file']['source'] = 'remote_file';
                        }
                        else
                        {
                            // If source_file is in local server, but reference by uri rather than path, decode recursively
                            $source_content = new content(str_replace(URI_SITE_BASE,'',$this->request['option']['source']));
                            $source_file_path = $source_content->request['file_path'];
                            unset($source_content);

                            if (!file_exists($source_file_path))
                            {
                                $this->message->error = 'Building: source file does not exist '.$source_file_path;
                                $this->result['status'] = 404;
                                return false;
                            }
                            $this->content['source_file']['original_file'] = $source_file_path;
                        }
                    }
                    else
                    {
                        // source file not leading by http is assumed to be local file path
                        if (!file_exists($this->request['option']['source']))
                        {
                            $this->message->error = 'Building: source file does not exist '.$this->request['option']['source'];
                            $this->result['status'] = 404;
                            return false;
                        }
                        $this->content['source_file']['original_file'] = $this->request['option']['source'];
                    }

                    if ($this->content['source_file']['source'] == 'remote_file')
                    {
                        // External source file
                        $file_header = @get_headers($this->content['source_file']['original_file'],true);
                        if (strpos( $file_header[0], '200 OK' ) === false)
                        {
                            // Error Handling, fail to get external source file header
                            $this->message->error = 'Source File not accessible - '.$file_header[0];
                            return false;
                        }
                        if (isset($file_header['Last-Modified']))
                        {
                            $this->content['source_file']['last_modified'] = strtotime($file_header['Last-Modified']);
                        }
                        else
                        {
                            if (isset($file_header['Expires']))
                            {
                                $this->content['source_file']['last_modified'] = strtotime($file_header['Expires']);
                            }
                            else
                            {
                                if (isset($file_header['Date'])) $this->content['source_file']['last_modified'] = strtotime($file_header['Date']);
                                else $this->content['source_file']['last_modified'] = ('+1 day');
                            }
                        }
                        if (isset($file_header['Content-Length']))
                        {
                            $this->content['source_file']['content_length'] = $file_header['Content-Length'];
                            if ($this->content['source_file']['content_length'] > 10485760)
                            {
                                // Error Handling, source file too big
                                $this->message->error = 'Source File too big ( > 10MB )';
                                return false;
                            }
                        }
                        if (isset($file_header['Content-Type']))
                        {
                            $this->content['source_file']['content_type'] = $file_header['Content-Type'];
                        }
                    }
                    else
                    {
//                        $this->content['source_file']['content_length'] = filesize($this->content['source_file']['original_file']);
//                        $this->content['source_file']['last_modified'] = filemtime($this->content['source_file']['original_file']);
//                        $this->content['source_file']['content_type'] = mime_content_type($this->content['source_file']['original_file']);
                        $this->content['source_file']['path'] = $this->content['source_file']['original_file'];
                    }
                }
                else
                {
                    if (file_exists($this->content['source_file']['path']))
                    {
//                        $this->content['source_file']['content_length'] = filesize($this->content['source_file']['path']);
//                        $this->content['source_file']['last_modified'] = filemtime($this->content['source_file']['path']);
                    }
                    elseif (file_exists(str_replace(PATH_ASSET,PATH_CONTENT,$this->content['source_file']['path'])))
                    {
                        // If source file does not exist in asset folder, check if there is a corresponding file in content folder
                        $this->content['source_file']['path'] = str_replace(PATH_ASSET,PATH_CONTENT,$this->content['source_file']['path']);
                    }
                    else
                    {
                        // If file source doesn't exist in content folder, try database
                        $view_class = 'view_'.$this->request['file_type'];
                        if (!class_exists($view_class))
                        {
                            // Error Handling, view class does not exist for given file type
                            $this->message->error = 'Building: cannot find source file - view class does not exist';
                            $this->result['status'] = 404;
                            return false;
                        }
                        switch ($this->request['file_type'])
                        {
                            case 'image':
                                if (empty($this->request['file_id']))
                                {
                                    // Error Handling, fail to get source file from database, last part of file name is not a valid id
                                    $this->message->error = 'Building: cannot find source file';
                                    $this->result['status'] = 404;
                                    return false;
                                }

                                $view_obj = new $view_class($this->request['file_id']);
                                break;
                            default:
                                print_r($this);exit;
                        }

                        if (!empty($view_obj->id_group))
                        {
                            $file_record = $view_obj->fetch_value();
                            $file_record = end($file_record);
                        }

                        if (empty($file_record['file_path']) OR !file_exists($file_record['file_path']))
                        {
                            $this->message->error = 'Building: cannot find source file - file does not exist';
                            $this->result['status'] = 404;
                            return false;
//                            $entity_class = 'entity_'.$this->request['file_type'];
//                            if (!class_exists($entity_class))
//                            {
//                                // Error Handling, last ditch failed, source file does not exist in database either
//                                $this->message->error = 'Building: cannot find source file';
//                                $this->result['status'] = 404;
//                                return false;
//                            }
//                            $entity_obj = new $entity_class($this->request['file_id']);
//                            if (empty($entity_obj->row))
//                            {
//                                // Error Handling, fail to get source file from database, cannot find matched record
//                                $this->message->error = 'Building: fail to get source file from database, invalid id';
//                                $this->result['status'] = 404;
//                                return false;
//                            }
//                            $entity_obj->sync(['sync_type'=>'update_current']);
//
//                            $view_obj->get();
//                            if (!empty($view_obj->id_group))
//                            {
//                                $file_record = end($view_obj->fetch_value());
//                            }
                        }

                        if (isset($file_record['file_size'])) $this->content['source_file']['content_length'] = $file_record['file_size'];
                        if (isset($file_record['update_time'])) $this->content['source_file']['last_modified'] = strtotime($file_record['update_time']);
                        if (isset($file_record['mime'])) $this->content['source_file']['content_type'] = $file_record['mime'];
                        if (isset($file_record['width'])) $this->content['source_file']['width'] = $file_record['width'];
                        if (isset($file_record['height'])) $this->content['source_file']['height'] = $file_record['height'];
                    }
                }

                if(!isset($this->content['source_file']['content_length'])) $this->content['source_file']['content_length'] = filesize($this->content['source_file']['path']);
                if(!isset($this->content['source_file']['last_modified'])) $this->content['source_file']['last_modified'] = filemtime($this->content['source_file']['path']);
                if(!isset($this->content['source_file']['content_type'])) $this->content['source_file']['content_type'] = mime_content_type($this->content['source_file']['path']);


                if ($this->content['target_file']['path'] == $this->content['source_file']['path'])
                {
                    if (isset($this->content['source_file']['content_length'])) $this->content['target_file']['content_length'] = $this->content['source_file']['content_length'];
                    if (isset($this->content['source_file']['last_modified'])) $this->content['target_file']['last_modified'] = $this->content['source_file']['last_modified'];
                    if (isset($this->content['source_file']['content_type'])) $this->content['target_file']['content_type'] = $this->content['source_file']['content_type'];
                }
                else
                {
                    if ($this->content['source_file']['last_modified'] > $this->content['target_file']['last_modified'])
                    {
                        if(file_exists($this->content['target_file']['path'])) unlink($this->content['target_file']['path']);
                    }

                    if (isset($this->content['source_file']['original_file']))
                    {
                        if ($this->content['source_file']['path'] == $this->content['source_file']['original_file'])
                        {
                            unset($this->content['source_file']['original_file']);
                        }
                        else
                        {
                            if ($this->content['source_file']['last_modified'] > $this->content['target_file']['last_modified'])
                            {
                                if (!file_exists(dirname($this->content['source_file']['path']))) mkdir(dirname($this->content['source_file']['path']), 0755, true);
                                copy($this->content['source_file']['original_file'],$this->content['source_file']['path']);
                                touch($this->content['source_file']['path'], $this->content['source_file']['last_modified']);

                                $this->content['source_file']['content_length'] = filesize($this->content['source_file']['path']);
                                $this->content['source_file']['content_type'] = mime_content_type($this->content['source_file']['path']);
                            }
                        }
                    }
                    foreach ($this->request['file_extra_extension'] as $extension_index=>$extension)
                    {
                        $this->content['target_file'][$extension_index] = $this->preference->{$this->request['file_type']}[$extension_index][$extension];
                    }

                }

                if ($this->request['file_type'] == 'image')
                {
                    switch($this->request['file_extension'])
                    {
                        case 'svg':
                            $this->content['format'] = 'svg';
                            break;
                        case 'jpg':
                        case 'png':
                        case 'gif':
                        default:
                            $source_image_size = getimagesize($this->content['source_file']['path']);
                            $this->content['source_file']['width'] = $source_image_size[0];
                            $this->content['source_file']['height'] = $source_image_size[1];

                            if (!isset($this->content['target_file']['width'])) $this->content['target_file']['width'] = $this->content['source_file']['width'];
                            if (!isset($this->content['target_file']['height'])) $this->content['target_file']['height'] = $this->content['source_file']['height'] / $this->content['source_file']['width'] * $this->content['target_file']['width'];

                            // If image quality is not specified, use the fast generate setting
                            if (!isset($this->content['target_file']['quality'])) $this->content['target_file']['quality'] = $this->preference->image['quality']['spd'];
                            break;
                    }
                }
                break;
            case 'data':
            default:
                $this->content['status'] = 'OK';
                $this->content['message'] = '';
                $this->content['field'] = array();
//                $this->content['script'] = array();
//                $this->content['style'] = array();
                $this->content['style'] = array('default'=>array());
                $this->content['script'] = array('jquery'=>array('source'=>PATH_CONTENT_JS.'jquery-1.11.3.js'),'default'=>array());

                $this->content['field']['base'] = URI_SITE_BASE;
                if ($this->preference->environment != 'production')
                {
                    $this->content['field']['robots'] = 'noindex, nofollow';
                }
                else
                {
                    $this->content['field']['robots'] = 'index, follow';
                }


                switch($this->request['control_panel'])
                {
                    case 'manager':
                        // Any request on members page need login account information, if not found, redirect to login page
                        if (empty($this->content['account']))
                        {
                            $this->message->error = 'Account not logged in or does not exist any more';
                            $this->result['status'] = 301;
                            $this->result['header']['Location'] =  URI_SITE_BASE.'login';
                            return false;
                        }

                        if (!empty($this->content['session']))
                        {
                            $this->result['cookie'] = array('session_id'=>array('value'=>$this->content['session']['name'],'time'=>strtotime($this->content['session']['expire_time'])));
                        }

                        $this->content['field']['robots'] = 'noindex, nofollow';
                        $this->content['field']['name'] = ucwords($this->request['control_panel']);
                        if (!empty($this->request['method']))
                        {
                            $this->content['field']['name'] .= ' - '.ucwords($this->request['method']);
                        }
                        $this->content['field']['name'] .= ' - '.$this->content['account']['name'];
                }

                switch($this->request['module'])
                {
                    case 'product':
                        switch($this->request['control_panel'])
                        {
                            case 'manager':
                                if (empty($this->request['category_id']) AND empty($this->request['product_id']))
                                {
                                    $entity_category_obj = new entity_category();
                                    $entity_category_obj->get(array('where'=>'display_order >= 0','order'=>'display_order'));
                                }
                                break;
                            default:
                                if (empty($this->request['category']))
                                {
                                    // If category is not set, product root page, display all category
                                    $view_category_obj = new view_category();
                                    $view_category_obj->get(array('where'=>'display_order >= 0','order'=>'display_order'));
                                    $page_obj = new view_web_page('product');
                                    $page_fetched_value = $page_obj->fetch_value(array('page_size'=>1));
                                    $page_fetched_value = end($page_fetched_value);
                                    $page_fetched_value['category'] = array_values($view_category_obj->id_group);

                                    $this->content['field'] = array_merge($this->content['field'],$page_fetched_value);
                                    $this->content['template_name'] = 'page_product_index';
//                                    $view_category_data = $view_category_obj->fetch_value(['page_size'=>8]);
                                }
                                else
                                {
                                    $view_category_obj = new view_category($this->request['category']);
                                    if (empty($view_category_obj->id_group))
                                    {
                                        // If there is category doesn't exist, redirect to product index
                                        $this->result['status'] = 301;
                                        $this->result['header']['Location'] =  URI_SITE_BASE.$this->request['module'].'/';
                                        break;
                                    }
                                    $index_product_obj = new index_product();
                                    $index_product_obj->filter_by_category(array('category_id'=>$view_category_obj->id_group,'where'=>array('`active` = 1')));

                                    $page_fetched_value = $view_category_obj->fetch_value(array('page_size'=>1));

                                    $this->content['field'] = array_merge($this->content['field'],end($page_fetched_value));
                                    $this->content['field']['product'] = array_values($index_product_obj->id_group);
                                }


                        }

                        break;
                    default:
                        // Default module, front end static pages, control panel home pages...
                        switch($this->request['control_panel'])
                        {
                            case 'manager':
                                // Members home page
                                $entity_web_page_obj = new entity_web_page();
                                $entity_web_page_obj->get(array('fields'=>['id','name'],'where'=>'friendly_uri != "product" AND friendly_uri != "login"'));
                                $this->content['field']['manage_menu_page'] = array_values($entity_web_page_obj->id_group);
                                if (!$this->request['option']['id'])
                                {
                                    //TODO: Error Handler for edit page without id
                                }
                                $entity_web_page_obj = new entity_web_page($this->request['option']['id']);
                                $this->content['field']['web_page'] = end($entity_web_page_obj->id_group);
                                $this->content['field']['page_content'] = '<a href="manager/list_page" class="general_style_input_button general_style_input_button_gray">Manage Page</a>
<a href="manager/list_category" class="general_style_input_button general_style_input_button_gray">Manage Product</a>';
                                break;
                            default:
                                // Front end home page and other statistic pages
                                // If page is login, check for user login session
                                if ($this->request['document'] == 'login')
                                {
                                    if (isset($this->request['session_id']))
                                    {
                                        // session_id is set, check if it is already logged in
                                        $entity_account_session_obj = new entity_account_session();
                                        $method_variable = array('status'=>'OK','message'=>'','account_session_id'=>$this->request['session_id'],'remote_ip'=>$this->request['remote_ip']);
                                        $session = $entity_account_session_obj->validate_account_session_id($method_variable);

                                        if ($session === false)
                                        {
                                            // If session_id is not valid, unset it and continue login process
                                            $this->result['cookie'] = array('session_id'=>array('value'=>'','time'=>1));
                                        }
                                        else
                                        {
                                            $entity_account_obj = new entity_account($session['account_id']);
                                            if (empty($entity_account_obj->row))
                                            {
                                                // Error Handling, session validation succeed, session_id is valid, but cannot read corresponding account
                                                $this->message->error = 'Session Validation Succeed, but cannot find related account';
                                                // If session_id is not valid, unset it and continue login process
                                                $this->result['cookie'] = array('session_id'=>array('value'=>'','time'=>1));
                                            }
                                            else
                                            {
                                                // If session is valid, redirect to console
                                                $this->result['cookie'] = array('session_id'=>array('value'=>$session['name'],'time'=>strtotime($session['expire_time'])));
                                                $this->result['status'] = 301;
                                                $this->result['header']['Location'] =  URI_SITE_BASE.'members/';

                                                return true;
                                            }
                                        }
                                    }
                                    if ($_SERVER['REQUEST_METHOD'] == 'POST')
                                    {
                                        if (isset($this->request['option']['username']))
                                        {
                                            $this->content['post_result'] = array(
                                                'status'=>'OK',
                                                'message'=>''
                                            );

                                            $login_param = array();
                                            $session_param = array();
                                            $login_param_keys = array('username','password','remember_me');
                                            foreach($this->request['option'] as  $option_key=>&$option_value)
                                            {
                                                if (in_array($option_key,$login_param_keys))
                                                {
                                                    $login_param[$option_key] = $option_value;
                                                    //unset($option_value);
                                                }
                                                elseif ($option_key == 'complementary')
                                                {
                                                    $complementary = base64_decode($option_value);
                                                    if ($complementary === false OR $complementary == $option_value)
                                                    {
                                                        // Error Handling, complementary info error, complementary is not base64 encoded text
                                                        $this->message->notice = 'Building: Login Failed';
                                                        $this->content['post_result'] = array(
                                                            'status'=>'REQUEST_DENIED',
                                                            'message'=>'Login Failed, please try again'
                                                        );
                                                        $this->result['cookie'] = array('session_id'=>array('value'=>'','time'=>1));
                                                    }
                                                    else
                                                    {
                                                        $complementary_info = json_decode($complementary,true);
                                                        if (empty($complementary_info))
                                                        {
                                                            // Error Handling, complementary info not in json format
                                                            $this->message->notice = 'Building: Login Failed';
                                                            $this->content['post_result'] = array(
                                                                'status'=>'REQUEST_DENIED',
                                                                'message'=>'Login Failed, please try again'
                                                            );
                                                            $this->result['cookie'] = array('session_id'=>array('value'=>'','time'=>1));
                                                        }
                                                        else
                                                        {
                                                            $session_param['remote_addr'] = $complementary_info['remote_addr'];
                                                            $session_param['http_user_agent'] = $complementary_info['http_user_agent'];
                                                        }
                                                    }
                                                }
                                            }

                                            if ($this->content['post_result']['status'] == 'OK')
                                            {
                                                $entity_account_obj = new entity_account();
                                                $account = $entity_account_obj->authenticate($login_param);
                                                if ($account === false)
                                                {
                                                    // Error Handling, login failed
                                                    $this->message->notice = 'Building: Login Failed';
                                                    $this->content['post_result'] = array(
                                                        'status'=>'REQUEST_DENIED',
                                                        'message'=>'Login Failed, invalid username or password'
                                                    );
                                                    $this->result['cookie'] = array('session_id'=>array('value'=>'','time'=>1));
                                                }
                                                else
                                                {

                                                    $session_expire = 86400;
                                                    if (!empty($login_param['remember_me']))
                                                    {
                                                        $session_expire = $session_expire*30;
                                                    }
                                                    $entity_account_session_obj = new entity_account_session();
                                                    $session_param = array_merge($session_param, array('account_id'=>$account['id'],'expire_time'=>gmdate('Y-m-d H:i:s',time()+$session_expire)));
                                                    $session = $entity_account_session_obj->generate_account_session_id($session_param);

                                                    if (empty($session))
                                                    {
                                                        // Error Handling, create session id failed
                                                        $this->message->error = 'Building: Fail to create session id';
                                                        $this->content['post_result'] = array(
                                                            'status'=>'REQUEST_DENIED',
                                                            'message'=>'Login Failed, fail to create new session'
                                                        );
                                                    }
                                                    else
                                                    {
                                                        $this->result['cookie'] = array('session_id'=>array('value'=>$session['name'],'time'=>time()+$session_expire));
                                                        $this->result['status'] = 301;
                                                        $this->result['header']['Location'] =  URI_SITE_BASE.'manager';
                                                    }
                                                }
                                            }
                                        }
                                        if ($this->content['post_result']['status'] != 'OK')
                                        {
                                            // If login failed, show error message
                                            $this->content['field']['post_result_message'] = '<div class="ajax_info ajax_info_error">'.$this->content['post_result']['message']."</div>";
                                        }

                                        // Record login event
                                        $entity_account_log_obj = new entity_account_log();
                                        $log_record = array('name'=>'Login','remote_ip'=>$this->request['remote_ip'],'request_uri'=>$_SERVER['REQUEST_URI']);
                                        $log_record = array_merge($log_record,$this->content['post_result']);
                                        if (isset($account['id']))
                                        {
                                            $log_record['account_id'] = $account['id'];
                                            $log_record['description'] =  $account['name'];
                                        }
                                        else
                                        {
                                            $log_record['description'] =  $this->request['option']['username'];
                                        }
                                        if (isset($session['name'])) $log_record['content'] = $session['name'];
                                        $entity_account_log_obj->set_log($log_record);
                                    }
                                }
                                if ($this->request['document'] == 'logout')
                                {
                                    // success or fail, logout page always redirect to login page after process complete
                                    $this->result['status'] = 301;
                                    $this->result['header']['Location'] =  URI_SITE_BASE.'login';
                                    if (!isset($this->request['session_id']))
                                    {
                                        // session_id is not set, redirect to login page
                                        return true;
                                    }
                                    $this->result['cookie'] = array('session_id'=>array('value'=>'','time'=>1));

                                    $entity_account_session_obj = new entity_account_session();
                                    $get_parameter = array(
                                        'bind_param' => array(':name'=>$this->request['session_id']),
                                        'where' => array('`name` = :name')
                                    );
                                    $entity_account_session_obj->get($get_parameter);

                                    if (count($entity_account_session_obj->row) > 0)
                                    {
                                        // Record logout event
                                        $session_record = end($entity_account_session_obj->row);

                                        $entity_account_log_obj = new entity_account_log();
                                        $log_record = array('name'=>'Logout','account_id'=>$session_record['account_id'],'status'=>'OK','message'=>'Session close by user','content'=>$session_record['name'],'remote_ip'=>$this->request['remote_ip'],'request_uri'=>$_SERVER['REQUEST_URI']);
                                        $entity_account_obj = new entity_account($session_record['account_id']);
                                        if (count($entity_account_obj->row) > 0)
                                        {
                                            $entity_account_row = end($entity_account_obj->row);
                                            $log_record['description'] = $entity_account_row['name'];
                                        }
                                        $entity_account_log_obj->set_log($log_record);
                                    }

                                    // If session is valid, delete the session then redirect to login
                                    $entity_account_session_obj->delete();
                                    return true;
                                }
                                if (isset($this->request['option']['field']))
                                {
                                    $this->content['field'] = array_merge($this->content['field'],$this->request['option']['field']);
                                }
                                else
                                {
                                    // Set field value from database
                                    if (!isset($this->request['document']))
                                    {
                                        $this->result['status'] = 404;
                                        return false;
                                    }
                                    $page_obj = new view_web_page($this->request['document']);
                                    if (empty($page_obj->id_group))
                                    {
                                        $this->result['status'] = 404;
                                        //$this->result['status'] = 301;
                                        //$this->result['header']['Location'] =  URI_SITE_BASE.'login';
                                        return false;
                                    }
                                    if (count($page_obj->id_group) > 1)
                                    {
                                        // Error Handling, ambiguous reference, multiple page found, database data error
                                        $GLOBALS['global_message']->warning = __FILE__.'(line '.__LINE__.'): Multiple web page resources loaded '.implode(',',$page_obj->id_group);
                                    }
                                    $page_fetched_value = $page_obj->fetch_value(array('page_size'=>1));
                                    if (empty($page_fetched_value))
                                    {
                                        // Error Handling, fetch record row failed, database data error
                                        $GLOBALS['global_message']->error = __FILE__.'(line '.__LINE__.'): Fetch row failed '.implode(',',$page_obj->id_group);
                                        $this->result['status'] = 404;
                                        return false;
                                    }
                                    $this->content['field'] = array_merge($this->content['field'],end($page_fetched_value));

                                    if ($this->request['document'] == 'login' OR $this->request['document'] == 'signup' )
                                    {
                                        $this->content['field']['complementary'] = base64_encode(json_encode(array('remote_addr'=>get_remote_ip(), 'http_user_agent'=>$_SERVER['HTTP_USER_AGENT'], 'submission_id'=>sha1(openssl_random_pseudo_bytes(5)))));
                                    }

                                }

                        }
                }

                if (isset($this->request['option']['template_name']))
                {
                    $this->content['template_name'] = $this->request['option']['template_name'];
                }
                else
                {
                    // Looking for default template
                    $template_name_part = array();
                    if (!empty($this->request['control_panel'])) $template_name_part[] = $this->request['control_panel'];
                    if (!empty($this->request['module'])) $template_name_part[] = $this->request['module'];
                    else $template_name_part[] = 'default';
                    if (!empty($this->request['method'])) $template_name_part[] = $this->request['method'];
                    if (isset($this->request['document']))
                    {
                        if ($this->request['document'] == '') $template_name_part[] = 'home';
                        else $template_name_part[] = $this->request['document'];
                    }
//print_r($template_name_part);
                    $default_css = array();
                    $default_js = array();
                    while (!empty($template_name_part))
                    {
                        if (file_exists(PATH_CONTENT_CSS.implode('_',$template_name_part).'.css'))
                        {
                            $default_css = array_merge(array(implode('_',$template_name_part)=>array()),$default_css);
                        }
                        if (file_exists(PATH_CONTENT_JS.implode('_',$template_name_part).'.js'))
                        {
                            $default_js = array_merge(array(implode('_',$template_name_part)=>array()),$default_js);
                        }
                        if (!isset($this->content['template_name']) AND file_exists(PATH_TEMPLATE.'page_'.implode('_',$template_name_part).FILE_EXTENSION_TEMPLATE))
                        {
                            $this->content['template_name'] = 'page_'.implode('_',$template_name_part);
                        }
                        array_pop($template_name_part);
                    }

                    $this->content['style'] = array_merge($this->content['style'],$default_css);
                    $this->content['script'] = array_merge($this->content['script'],$default_js);
                    if (!isset($this->content['template_name'])) $this->content['template_name'] = 'page_default';
                }
            //print_r($this->content['script']);exit();


                //$this->result['content'] = render_html($this->content['field'],$this->content['template_name']);


                return true;
        }

        return true;
    }

    private function generate_rendering()
    {
        switch($this->content['format'])
        {
            case 'css':
            case 'js':
                $target_file_path = dirname($this->content['target_file']['path']);
                if (!file_exists($target_file_path)) mkdir($target_file_path, 0755, true);

                if (!file_exists($this->content['target_file']['path']) OR $this->content['source_file']['last_modified'] > $this->content['target_file']['last_modified'])
                {
                    if (!empty($this->content['target_file']['minify']))
                    {
                        // Yuicompressor 2.4.8 does not support output as Windows absolute path start with Driver
                        $start_time = microtime(true);
                        $execution_command = 'java -jar '.PATH_CONTENT_JAR.'yuicompressor-2.4.8.jar --type '.$this->content['format'].' "'.$this->content['source_file']['path'].'" -o "'.preg_replace('/^\w:/','',$this->content['target_file']['path']).'"';
                        exec($execution_command, $result);
                        $this->message->notice = 'Yuicompressor Execution Time: '. (microtime(true) - $start_time);
                        $this->message->notice = $execution_command;
                        //$this->message->notice = $result;
                    }

                    if (!file_exists($this->content['target_file']['path']))
                    {
                        // If fail to generate minimized file, copy the source file
                        copy($this->content['source_file']['path'], $this->content['target_file']['path']);
                    }
                    else
                    {
                        if (filesize($this->content['target_file']['path']) > $this->content['source_file']['content_length'])
                        {
                            // If file getting bigger, original file probably already minimized with better algorithm (e.g. google's js files, just use the original file)
                            copy($this->content['source_file']['path'], $this->content['target_file']['path']);
                        }
                    }
                    if (!empty($this->content['target_file']['minify']))
                    {
                        $start_time = microtime(true);
                        file_put_contents($this->content['target_file']['path'],minify_content(file_get_contents($this->content['target_file']['path']),$this->request['file_extension']));
                        $this->message->notice = 'PHP Minifier Execution Time: '. (microtime(true) - $start_time);
                    }
                    touch($this->content['target_file']['path'],$this->content['source_file']['last_modified']);
                }

                if (!file_exists($this->content['target_file']['path']))
                {
                    // Error Handling, Fail to generate target file
                    $this->message->error = 'Rendering: Fail to generate target file';
                    return false;
                }

                if ($this->request['file_uri'] != $this->content['target_file']['uri'])
                {
                    // On Direct Rendering from HTTP REQUEST, if request_uri is different from target file_uri, do 301 redirect
                    $this->result['status'] = 301;
                    $this->result['header']['Location'] = str_replace(URI_SITE_BASE,'/',$this->content['target_file']['uri']);
                    return false;
                }

                $this->content['target_file']['last_modified'] = filemtime($this->content['target_file']['path']);
                $this->content['target_file']['content_length'] = filesize($this->content['target_file']['path']);

                if ($this->content['target_file']['content_length'] == 0)
                {
                    // Error Handling, Fail to generate target file
                    $this->message->error = 'Rendering: Fail to generate target file';
                    return false;
                }

                $this->result['header']['Last-Modified'] = gmdate('D, d M Y H:i:s',$this->content['target_file']['last_modified']).' GMT';
                $this->result['header']['Content-Length'] = $this->content['target_file']['content_length'];

                switch ($this->request['file_extension'])
                {
                    case 'css':
                        $this->result['header']['Content-Type'] = 'text/css';
                        break;
                    case 'js':
                        $this->result['header']['Content-Type'] = 'application/javascript';
                        break;
                    default:
                }

                $this->result['file_path'] = $this->content['target_file']['path'];
                break;
            case 'file_uri':
                $this->result['content'] = $this->content['target_file']['uri'];
                break;
            case 'image':
                // create source file resource object
                switch ($this->content['source_file']['content_type']) {
                    case 'image/png':
                        $source_image = imagecreatefrompng($this->content['source_file']['path']);
                        break;
                    case 'image/gif':
                        $source_image = imagecreatefromgif($this->content['source_file']['path']);
                        break;
                    case 'image/jpg':
                    case 'image/jpeg':
                        $source_image = imagecreatefromjpeg($this->content['source_file']['path']);
                        break;
                    default:
                        $source_image = imagecreatefromstring($this->content['source_file']['path']);
                }
                if ($source_image === FALSE) {
                    // Error Handling, fail to create image
                    $this->message->error = 'Rendering: fail to create image';
                    return false;
                }

                // If the required file is not the default file, generate the required file
                if ($this->content['source_file']['path'] != $this->content['target_file']['path'])
                {
                    if ($this->content['source_file']['width'] != $this->content['target_file']['width'])
                    {
                        // Resize the image if it is not the same size
                        $target_image = imagecreatetruecolor($this->content['target_file']['width'],  $this->content['target_file']['height']);
                        imagecopyresampled($target_image,$source_image,0,0,0,0,$this->content['target_file']['width'], $this->content['target_file']['height'],$this->content['source_file']['width'],$this->content['source_file']['height']);
                    }
                    else
                    {
                        $target_image = $source_image;
                    }
                    imageinterlace($target_image,true);

                    $image_quality = $this->content['target_file']['quality'];
                    ob_start();
                    switch($this->content['source_file']['content_type'])
                    {
                        case 'image/png':
                            imagesavealpha($target_image, true);
                            imagepng($target_image, NULL, $image_quality['image/png'][0], $image_quality['image/png'][1]);
                            $this->content['target_file']['content_type'] = 'image/png';
                            break;
                        case 'image/gif':
                        case 'image/jpg':
                        case 'image/jpeg':
                        default:
                            imagejpeg($target_image, NULL, $image_quality['image/jpeg']);
                            $this->content['target_file']['content_type'] = 'image/jpeg';
                    }
                    $this->result['content'] = ob_get_contents();
                    ob_get_clean();
                    if (!file_exists(dirname($this->content['target_file']['path']))) mkdir(dirname($this->content['target_file']['path']), 0755, true);
                    file_put_contents($this->content['target_file']['path'],$this->result['content']);
                    //$this->content['target_file']['content_length'] = filesize($this->content['target_file']['path']);
                    $this->content['target_file']['content_length'] = strlen($this->result['content']);
                    touch($this->content['target_file']['path'],$this->content['source_file']['last_modified']);
                    $this->content['target_file']['last_modified'] = $this->content['source_file']['last_modified'];

                    // Default image create process finish here, unset default file gd object
                    unset($target_image);
                }
                if (empty($this->content['target_file']['last_modified']) AND file_exists($this->content['target_file']['path'])) $this->content['target_file']['last_modified'] = filemtime($this->content['target_file']['path']);

                $this->result['header']['Last-Modified'] = gmdate('D, d M Y H:i:s',$this->content['target_file']['last_modified']).' GMT';
                $this->result['header']['Content-Length'] = $this->content['target_file']['content_length'];
                $this->result['header']['Content-Type'] = $this->content['target_file']['content_type'];

                if (!isset($this->result['content'])) $this->result['file_path'] = $this->content['target_file']['path'];
                break;
            case 'svg':
                $this->content['target_file']['last_modified'] = 'image/svg+xml';

                // TODO: svg is basically xml file, file_get_content -> minify as html -> file_put_content?
                if ($this->content['source_file']['path'] != $this->content['target_file']['path'])
                {
                    copy($this->content['source_file']['path'],$this->content['target_file']['path']);
                    touch($this->content['target_file']['path'],$this->content['source_file']['last_modified']);
                    $this->content['target_file']['last_modified'] = $this->content['source_file']['last_modified'];
                    $this->content['target_file']['last_modified'] = $this->content['source_file']['content_length'];
                }

                $this->result['header']['Last-Modified'] = gmdate('D, d M Y H:i:s',$this->content['target_file']['last_modified']).' GMT';
                $this->result['header']['Content-Length'] = $this->content['target_file']['content_length'];
                $this->result['header']['Content-Type'] = $this->content['target_file']['content_type'];

                if (!isset($this->result['content'])) $this->result['file_path'] = $this->content['target_file']['path'];
                break;
            case 'json':
                if (!isset($GLOBALS['global_field'])) $GLOBALS['global_field'] = array();
                switch($this->request['control_panel'])
                {
                    case 'manager':
                        switch($this->request['module'])
                        {
                            case 'listing':
                                switch($this->request['method'])
                                {
                                    case 'edit':
//                                        switch($this->request['action'])
//                                        {
//                                            case 'save':
//                                                $this->result['content']['form'] = $this->request['option'];
//                                                break;
//                                        }
                                        break;
                                    case '':
                                        $this->result['content']['html'] = render_html(array('_value'=>$this->content['field'],'_parameter'=>array('template'=>'[[organization:template_name=`view_members_organization_summary`]]')));
                                        break;
                                }
                                break;
                        }
                        break;
                    default:
                        switch($this->request['module'])
                        {
                            case 'listing':
                                switch($this->request['method'])
                                {
                                    case '':
                                        $this->result['content']['html'] = render_html(array('_value'=>$this->content['field'],'_parameter'=>array('template'=>'[[category]]')));
                                        break;
                                }
                                break;
                        }

                }
                if (isset($GLOBALS['global_field']['style']))
                {
                    $combined_content = '';
                    foreach($GLOBALS['global_field']['style'] as $index=>$item)
                    {
                        $combined_content .= $item['content'];
                    }
                    $this->result['content']['style'] = $combined_content;
                    unset($combined_content);
                }
                if (isset($GLOBALS['global_field']['script']))
                {
                    $combined_content = '';
                    foreach($GLOBALS['global_field']['script'] as $index=>$item)
                    {
                        $combined_content .= $item['content'];
                    }
                    $this->result['content']['script'] = $combined_content;
                    unset($combined_content);
                }
                $this->result['content'] = json_encode($this->result['content']);
                //$this->result['content'] = json_encode($this->content['api_result']);
                $this->result['header']['Last-Modified'] = gmdate('D, d M Y H:i:s').' GMT';
                $this->result['header']['Content-Length'] = strlen($this->result['content']);
                $this->result['header']['Content-Type'] = 'application/json';
                break;
            case 'xml':
                $this->result['content'] = render_xml($this->content['api_result'])->asXML();
                $this->result['header']['Last-Modified'] = gmdate('D, d M Y H:i:s').' GMT';
                $this->result['header']['Content-Length'] = strlen($this->result['content']);
                $this->result['header']['Content-Type'] = 'text/xml';
                break;
            case 'html':
                if (!isset($this->content['field'])) $this->content['field'] = array();
                if (!isset($this->content['template_name'])) $this->content['template_name'] = '';
                $GLOBALS['global_field'] = array();
                $this->result['content'] = render_html(array('_value'=>$this->content['field'],'_parameter'=>array('template_name'=>$this->content['template_name'])));
//echo 'test point 3'."\n";
//print_r($GLOBALS['global_field']);
                if (isset($GLOBALS['global_field']['style'])) $this->content['style'] = array_merge($this->content['style'],$GLOBALS['global_field']['style']);
                if (isset($GLOBALS['global_field']['script'])) $this->content['script'] = array_merge($this->content['script'],$GLOBALS['global_field']['script']);
                $this->result['content'] = preg_replace('/\[\[\+/','[[*',$this->result['content']);
                if (!empty($this->content['style']))
                {
//print_r($this->content['style']);
                    $this->content['field']['style'] = array('_value'=>array(),'_parameter'=>array('template_name'=>'chunk_html_tag'));
                    $file_extension = '.css';
                    if ($this->preference->minify_css)
                    {
                        $file_extension = '.min'.$file_extension;
                    }
                    foreach($this->content['style'] as $name=>$option)
                    {
                        $attribute = array(
                            'type'=>'text/css'
                        );
                        if (!isset($option['content']))
                        {
                            $tag = array(
                                'name'=>'link',
                                'non_void_element'=>false
                            );
                            $attribute['rel'] = 'stylesheet';
                            // TODO: during development, use content css directly
//                            if (!empty($option['name'])) $attribute['href'] = URI_CSS.$option['name'].$file_extension;
//                            else $attribute['href'] = URI_CSS.$name.$file_extension;
                            if (!empty($option['name'])) $attribute['href'] = URI_CONTENT_CSS.$option['name'].$file_extension;
                            else $attribute['href'] = URI_CONTENT_CSS.$name.$file_extension;
                        }
                        else
                        {
                            $tag = array(
                                'name'=>'style',
                                'non_void_element'=>true,
                                'content'=>$option['content']
                            );
                        }

                        if (isset($option['attribute']))  $attribute = array_merge($attribute,$option['attribute']);
                        $attribute_set = array();
                        foreach($attribute as $attribute_name=>$attribute_value)
                        {
                            $attribute_set[] = array('name'=>$attribute_name,'value'=>$attribute_value);
                        }
                        $tag['attribute'] = $attribute_set;
                        unset($attribute_set);

                        $this->content['field']['style']['_value'][] = $tag;
                        unset($tag);
                    }
                }
                if (!empty($this->content['script']))
                {
                    $this->content['field']['script'] = array('_value'=>array(),'_parameter'=>array('template_name'=>'chunk_html_tag'));
                    $file_extension = '.js';
                    if ($this->preference->minify_js)
                    {
                        $file_extension = '.min'.$file_extension;
                    }
                    foreach($this->content['script'] as $name=>$option)
                    {
                        $tag = array(
                            'name'=>'script',
                            'non_void_element'=>true
                        );
                        if (isset($option['content']))  $tag['content'] = $option['content'];
                        $attribute = array(
                            'type'=>'text/javascript'
                        );
                        if (!isset($option['content']))
                        {
                            $attribute['src'] = URI_JS.$name.$file_extension;
                        }

                        if (isset($option['source']))
                        {
                            $attribute['src'] .= '?source='.urlencode($option['source']);
                        }

                        if (isset($option['attribute']))  $attribute = array_merge($attribute,$option['attribute']);
                        $attribute_set = array();
                        foreach($attribute as $attribute_name=>$attribute_value)
                        {
                            $attribute_set[] = array('name'=>$attribute_name,'value'=>$attribute_value);
                        }
                        $tag['attribute'] = $attribute_set;
                        unset($attribute_set);

                        $this->content['field']['script']['_value'][] = $tag;
                        unset($tag);
                    }
                }
                $this->result['content'] = render_html(array('_value'=>$this->content['field'],'_parameter'=>array('template'=>$this->result['content'])));

                $this->result['header']['Last-Modified'] = gmdate('D, d M Y H:i:s').' GMT';
                $this->result['header']['Content-Length'] = strlen($this->result['content']);
                $this->result['header']['Content-Type'] = 'text/html';
                break;
        }
    }

    function render()
    {
        session_start();
        if (isset($this->result['cookie']))
        {
            foreach($this->result['cookie'] as $cookie_name=>$cookie_content)
            {
                setcookie($cookie_name,$cookie_content['value'],$cookie_content['time'],'/'.(FOLDER_SITE_BASE != ''?(FOLDER_SITE_BASE.'/'):''));
            }
        }

        http_response_code($this->result['status']);
        foreach($this->result['header'] as $header_name=>$header_content)
        {
            header($header_name.': '.$header_content);
        }
        if (isset($this->result['file_path']))
        {
            readfile($this->result['file_path']);
            exit();
        }
        if (!empty($this->result['content']))
        {
            print_r($this->result['content']);
        }
    }

    function get_result()
    {
        if (isset($this->result['file_path']))
        {
            return file_get_contents($this->result['file_path']);
        }
        return $this->result['content'];
    }
}