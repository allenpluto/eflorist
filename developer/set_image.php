<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 8/02/2017
 * Time: 2:12 PM
 */
define('PATH_SITE_BASE', dirname(__DIR__).DIRECTORY_SEPARATOR);
include('../system/config/config.php');
$start_stamp = microtime(1);
echo '<pre>';

// TEST IMAGE ENTITY
$row = ['name'=>'Florist Event Arrangement','source_file'=>'http://efloristwagga.com.au/assets/images/home_slide/slide4.jpg'];
$entity_image = new entity_image();
$entity_image->set(['row'=>[$row]]);
print_r($entity_image);
exit;