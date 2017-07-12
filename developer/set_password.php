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

$entity_image = new entity_account(1);
$entity_image->update(['password'=>'twmg2011']);
print_r($entity_image);
exit;