<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 25/10/2016
 * Time: 3:00 PM
 */
if (!isset($global_preference)) $global_preference = preference::get_instance();

// JS file option, minify content
$global_preference->html = array(
    'minify'=>array(
        'min'=>array(
            'content'=>true,
            'inline_js'=>true,
            'inline_css'=>true
        ),
        ''=>array(
            'content'=>false,
            'inline_js'=>false,
            'inline_css'=>false
        )
    )
);
