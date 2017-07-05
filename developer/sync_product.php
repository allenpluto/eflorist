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


$entity_product = new entity_product();
$entity_product->sync(['sync_type'=>'init_sync']);

print_r($entity_product);
exit;