<?php
/**
 * Created by PhpStorm.
 * User: llj
 * Date: 29/06/2017
 * Time: 10:52 PM
 */

$source_html = '<div style="font-size: 18px;">
<div><span style="font-size: 120%; color: #993300;"><strong style="line-height: 1.5;">Southcity Florist (shop)</strong></span></div>
<div><strong style="line-height: 1.5;">Kiosk 2, Southcity Shopping Centre,</strong></div>
<div><strong>
Glenfield Park, Wagga Wagga 2650<br />
Tel: (02) 6931 4562</strong></div>
<br />
<div><span style="font-size: 100%; color: #993300;"><strong>Flowers on Chaston (workshop)</strong></span></div>
<div><strong><span>50 Chaston Street, Wagga Wagga 2650</span></strong></div>
<div><strong><span>Tel: (02)&nbsp;<span>6971 3819</span></span></strong></div>
<div><strong>
<br />Shop Trading Hours<br />
Mon to Fri: 0900 &ndash; 1800</strong></div>
<div><strong>Sat: 0900 - 1600<br />
Sun: 0900 &ndash; 1300</strong></div>
<div><strong>&nbsp;</strong></div>
<div><span style="font-size: 80%;">ABN: 33 160 165 776
</span></div>
</div>';
$encoded = json_encode(['home_slide'=>[1,2,3,4],'page_content_2'=>$source_html]);
$decoded = json_decode($encoded,true);
print_r($encoded);
print_r($decoded);