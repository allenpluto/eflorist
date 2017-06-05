<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 25/10/2016
 * Time: 3:00 PM
 */
if (!isset($global_preference)) $global_preference = preference::get_instance();

// JS file option, minify content
$global_preference->js = array(
    'minify'=>array(
        'min'=>array(
            'yui'=>true,
            'php'=>true
        ),
        ''=>array(
            'yui'=>false,
            'php'=>false
        )
    )
);
