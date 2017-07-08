<?php
// Class Object
// Name: entity_image
// Description: Image Source File, store image in big size, for gallery details, source file to crop... All variation of images (different size thumbs) goes to image_variation table.

// image_id in image_object reference to source image. One source image may have zero to multiple thumbnail (cropped versions) for different scenario. Only source image may save exifData, any thumbnail can be regenerated using source image exifData and 
class entity_image extends entity
{
    function __construct($value = Null, $parameter = array())
    {
        include_once(PATH_PREFERENCE.'image'.FILE_EXTENSION_INCLUDE);

        $default_parameter = array(
            'store_data'=>true
        );
        $parameter = array_merge($default_parameter, $parameter);
        parent::__construct($value, $parameter);

        if (!$this->parameter['store_data'])
        {
            unset($this->parameter['table_fields']['data']);
        }

        return $this;
    }

    function set($parameter = array())
    {
        if (isset($parameter['row']))
        {
            $max_image_width = max($this->preference->image['width']);
            $image_quality = $this->preference->image['quality']['max'];

            foreach($parameter['row'] as $record_index => &$record)
            {
                if (isset($record['source_file']))
                {
                    $image_size = @getimagesize($record['source_file']);
                    if ($image_size !== false)
                    {
                        $record['width'] = $image_size[0];
                        $record['height'] = $image_size[1];
                        if (isset($image_size['mime'])) $record['mime'] = $image_size['mime'];
                        else
                        {
                            $record['mime'] = 'image/jpeg';
                            $this->message->notice = __FILE__ . '(line ' . __LINE__ . '): '.$parameter['table'].' failed to get image mime type for '.$record['source_file'];
                        }

                        $image_data = file_get_contents($record['source_file']);

                        if ($image_data !== false)
                        {
                            if ($record['width'] > $max_image_width AND ($record['mime'] == 'image/jpeg' OR $record['mime'] == 'image/png'))
                            {
                                $original_record = $record;
                                $record['height'] = $record['height'] / $record['width'] * $max_image_width;
                                $record['width'] = $max_image_width;

                                // Set default image quality as 'max'
                                //$record['quality'] = $this->preference->image['quality']['max'];
                                $source_image = imagecreatefromstring($image_data);
                                $target_image = imagecreatetruecolor($record['width'],  $record['height']);

                                imagecopyresampled($target_image,$source_image,0,0,0,0,$record['width'], $record['height'],$original_record['width'],$original_record['height']);
                                imageinterlace($target_image,true);

                                ob_start();
                                if ($record['mime'] == 'image/jpeg') imagejpeg($target_image, NULL, $image_quality['image/jpeg']);
                                if ($record['mime'] == 'image/png')
                                {
                                    imagesavealpha($target_image, true);
                                    imagepng($target_image, NULL, $image_quality['image/png'][0], $image_quality['image/png'][1]);
                                }
                                $image_data = ob_get_contents();
                                ob_get_clean();

                                imagedestroy($source_image);
                                imagedestroy($target_image);
                                unset($original_record);
                            }

                            $record['data'] = $image_data;
                        }
                        else
                        {
                            $this->message->warning = __FILE__ . '(line ' . __LINE__ . '): '.$parameter['table'].' failed to get image data for '.$record['source_file'];
                        }
                        unset($image_data);
                    }
                    else
                    {
                        $this->message->warning = __FILE__ . '(line ' . __LINE__ . '): '.$parameter['table'].' failed to get image size for '.$record['source_file'];
                    }
                    unset($image_size);
                    if (preg_match('/^data:/',$record['source_file']))
                    {
                        $record['source_file'] = '';
                    }
                }
            }
        }

        $result = parent::set($parameter);
        return $result;
    }

    function sync($parameter = array())
    {
        $sync_parameter = array();

        // set default sync parameters for index table
        //$parameter['sync_table'] = str_replace('entity','index',$this->parameter['table']);
        $sync_parameter['sync_table'] = 'tbl_view_image';
        $sync_parameter['update_fields'] = array(
            'id' => 'tbl_entity_image.id',
            'friendly_uri' => 'tbl_entity_image.friendly_uri',
            'name' => 'tbl_entity_image.name',
            'alternate_name' => 'tbl_entity_image.alternate_name',
            'description' => 'tbl_entity_image.description',
            'enter_time' => 'tbl_entity_image.enter_time',
            'update_time' => 'tbl_entity_image.update_time',
            'view_time' => '"'.date('Y-m-d H:i:s').'"',
            'width' => 'tbl_entity_image.width',
            'height' => 'tbl_entity_image.height',
            'mime' => 'tbl_entity_image.mime'
        );
        $sync_parameter['advanced_sync_fetch_fields'] = array(
            'data' => 'tbl_entity_image.data',
            'source_file' => 'tbl_entity_image.source_file'
        );
        $sync_parameter['advanced_sync_update_fields'] = array(
            'file_extension' => 'VARCHAR(20) NOT NULL DEFAULT ""',
            'file_uri' => 'VARCHAR(200) NOT NULL DEFAULT ""',
            'file_path' => 'VARCHAR(200) NOT NULL DEFAULT ""',
            'file_size' => 'INT(100) NOT NULL DEFAULT "0"'
        );
        $sync_parameter['advanced_sync'] = true;

        $sync_parameter = array_merge($sync_parameter, $parameter);

        if ($GLOBALS['db']) $db = $GLOBALS['db'];
        else $db = new db;

        if (!isset($sync_parameter['sync_type']))
        {
            $sync_parameter['sync_type'] = 'differential_sync';
        }
        if (!$db->db_table_exists($sync_parameter['sync_table']))
        {
            $sync_parameter['sync_type'] = 'init_sync';
        }

        if ($sync_parameter['sync_type'] == 'init_sync')
        {
            $init_sync_parameter = $sync_parameter;
            unset($init_sync_parameter['update_fields']['data']);
//            $init_sync_parameter['update_fields']['file_extension'] = '"'.str_repeat(' ',20).'"';
//            $init_sync_parameter['update_fields']['file_uri'] = '"'.str_repeat(' ',200).'"';
//            $init_sync_parameter['update_fields']['file_path'] = '"'.str_repeat(' ',200).'"';
//            $init_sync_parameter['update_fields']['file_size'] = 10^10;
//            parent::sync($init_sync_parameter);
//
//            $sync_parameter['sync_type'] = 'differential_sync';
//print_r($sync_parameter);
//exit;
            return parent::sync($sync_parameter);
        }
        else
        {
            return parent::sync($sync_parameter);
        }
    }

    function advanced_sync_update(&$source_row = array())
    {
        parent::advanced_sync_update($source_row);

        $max_image_width = max($this->preference->image['width']);
        $image_quality = $this->preference->image['quality']['max'];

        foreach ($source_row as $record_index=>&$record)
        {
            if (empty($record['id']))
            {
                $this->message->error = __FILE__ . '(line ' . __LINE__ . '): image sync row id not set';
            }
            if (empty($record['friendly_uri']))
            {
                $this->message->error = __FILE__ . '(line ' . __LINE__ . '): image sync row friendly uri not set';
            }
            $sub_path = '';
            $sub_path_index = $record['id'];
            do
            {
                $sub_path_remain = $sub_path_index % 1000;
                $sub_path_index = floor($sub_path_index / 1000);
                if ($sub_path_index != 0)
                {
                    $sub_path_remain = str_repeat('0', 3-strlen($sub_path_remain)).$sub_path_remain;
                }
                $sub_path = $sub_path_remain.DIRECTORY_SEPARATOR.$sub_path;
            } while($sub_path_index > 0);
            $file_dir = PATH_IMAGE.$sub_path;
            $file_name = $record['friendly_uri'];
            switch($record['mime'])
            {
                case 'image/gif':
                    $record['file_extension'] = 'gif';
                    break;
                case 'image/png':
                    $record['file_extension'] = 'png';
                    break;
                case 'image/jpeg':
                case 'image/pjpeg';
                default:
                    $record['file_extension'] = 'jpg';
            }
            $record['file_uri'] = URI_IMAGE.$file_name.'-'.$record['id'].'.'.$record['file_extension'];
            $record['file_path'] = $file_dir.$file_name.'-'.$record['id'].'.'.$record['file_extension'];

            if (!empty($record['data']))
            {
                if (!file_exists($file_dir)) mkdir($file_dir, 0755, true);
                $file_put_contents_result = file_put_contents($record['file_path'],  $record['data']);
                $this->message->notice = 'Set file from data: '.$record['file_path'].' '.$file_put_contents_result;
            }
            elseif (!empty($record['source_file']))
            {
                $image_size = @getimagesize($record['source_file']);
                if ($image_size !== false)
                {
                    $record['width'] = $image_size[0];
                    $record['height'] = $image_size[1];
                    if (isset($image_size['mime'])) $record['mime'] = $image_size['mime'];
                    else
                    {
                        $record['mime'] = 'image/jpeg';
                        $this->message->notice = __FILE__ . '(line ' . __LINE__ . '): '.$this->parameter['table'].' failed to get image mime type for '.$record['source_file'];
                    }

                    $image_fetch_log = PATH_ASSET.'log'.DIRECTORY_SEPARATOR.'image_fetch_log.txt';
                    if (!file_exists(dirname($image_fetch_log))) mkdir(dirname($image_fetch_log), 0755, true);
                    file_put_contents($image_fetch_log,'REQUEST IMAGE: '.$record['source_file']."\n",FILE_APPEND);
                    $image_data = file_get_contents($record['source_file']);

                    if ($image_data !== false)
                    {
                        if ($record['width'] > $max_image_width AND ($record['mime'] == 'image/jpeg' OR $record['mime'] == 'image/png'))
                        {
                            $original_record = $record;
                            $record['height'] = $record['height'] / $record['width'] * $max_image_width;
                            $record['width'] = $max_image_width;

                            // Set default image quality as 'max'
                            //$record['quality'] = $this->preference->image['quality']['max'];
                            $source_image = imagecreatefromstring($image_data);
                            $target_image = imagecreatetruecolor($record['width'],  $record['height']);

                            imagecopyresampled($target_image,$source_image,0,0,0,0,$record['width'], $record['height'],$original_record['width'],$original_record['height']);
                            imageinterlace($target_image,true);

                            ob_start();
                            if ($record['mime'] == 'image/jpeg') imagejpeg($target_image, NULL, $image_quality['image/jpeg']);
                            if ($record['mime'] == 'image/png')
                            {
                                imagesavealpha($target_image, true);
                                imagepng($target_image, NULL, $image_quality['image/png'][0], $image_quality['image/png'][1]);
                            }
                            $image_data = ob_get_contents();
                            ob_get_clean();

                            imagedestroy($source_image);
                            imagedestroy($target_image);
                            unset($original_record);
                        }
                        if (!file_exists($file_dir)) mkdir($file_dir, 0755, true);
                        $file_put_contents_result = file_put_contents($record['file_path'],  $image_data);
                        $this->message->notice = 'Set file from data: '.$record['file_path'].' '.$file_put_contents_result;
                    }
                    else
                    {
                        $this->message->warning = __FILE__ . '(line ' . __LINE__ . '): tbl_entity_image failed to get image data for '.$record['source_file'];
                    }
                    unset($image_data);
                }
                else
                {
                    $this->message->warning = __FILE__ . '(line ' . __LINE__ . '): tbl_entity_image failed to get image size for '.$record['source_file'];
                }
                unset($image_size);
            }
            else
            {
                $this->message->warning = __FILE__ . '(line ' . __LINE__ . '): tbl_entity_image failed to generate image on sync, source file and data are not set in id:'.$record['id'];
            }

//            unset($record['data']);
//            unset($record['source_file']);

            $record['file_size'] = filesize($record['file_path']);
        }

        return $source_row;
    }

    function advanced_sync_delete($delete_id_group = array())
    {
        $row = $this->get();

        if ($row == false)
        {
            $this->message->warning = 'Invalid delete id(s) ['.implode(',',$delete_id_group).']. id provided might have been deleted already.';
            return false;
        }

        foreach ($row as $index=>$record)
        {
            $sub_path = '';
            $sub_path_index = $record['id'];
            do
            {
                $sub_path_remain = $sub_path_index % 1000;
                $sub_path_index = floor($sub_path_index / 1000);
                if ($sub_path_index != 0)
                {
                    $sub_path_remain = str_repeat('0', 3-strlen($sub_path_remain)).$sub_path_remain;
                }
                $sub_path = $sub_path_remain.DIRECTORY_SEPARATOR.$sub_path;
            } while($sub_path_index > 0);
            $current_image_folder = PATH_IMAGE.$sub_path;
            if (file_exists($current_image_folder))
            {
                $current_image_files = scandir($current_image_folder);
                foreach ($current_image_files as $current_image_file_index=>$current_image_file)
                {
                    if (is_file($current_image_file)) unlink($current_image_file);
                }
                while (@rmdir($current_image_folder))
                {
                    $current_image_folder = dirname($current_image_folder);
                }
            }
        }
       return true;
    }
}