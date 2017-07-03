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
$row = [
    ['name'=>'Bouquet','source_file'=>'http://efloristwagga.com.au/assets/galleries/54/wagga-florist-238.JPG'],
    ['name'=>'Arrangement','source_file'=>'http://efloristwagga.com.au/assets/galleries/55/wagga-florist-218.JPG'],
    ['name'=>'New Born','source_file'=>'http://efloristwagga.com.au/assets/galleries/56/wagga-florist-013a.JPG'],
    ['name'=>'Sympathy','source_file'=>'http://efloristwagga.com.au/assets/galleries/57/2014-07-02_001.JPG'],
    ['name'=>'Wedding','source_file'=>'http://efloristwagga.com.au/assets/galleries/58/img_0031.JPG'],
    ['name'=>'Artificial','source_file'=>'http://efloristwagga.com.au/assets/galleries/59/wagga-florist_006.JPG'],
    ['name'=>'Fruit Hamper','source_file'=>'http://efloristwagga.com.au/assets/galleries/60/wagga-florist-010a.JPG'],
    ['name'=>'Gift Ideas','source_file'=>'http://efloristwagga.com.au/assets/galleries/61/2015-07-14_011.jpg']
];
$entity_image = new entity_image();
$entity_image->set(['row'=>$row]);
print_r($entity_image);
exit;