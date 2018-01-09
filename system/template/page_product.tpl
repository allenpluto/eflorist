<!doctype html>
<html lang="en">
[[$chunk_head]]
<body>
<div class="wrapper off_canvas_wrapper">
    <div class="wrapper off_canvas_container">
        <div class="off_canvas_container_mask off_canvas_halt"></div>
        [[$chunk_menu]]
        [[$chunk_header]]
        <div class="wrapper body_wrapper">
            <div class="container body_container product_body_container">
                <div class="product_detail_title"><h1>[[*name]]</h1></div>
                [[product_detail:object=`view_product`:template_name=`view_product_detail`]]
                <div class="product_list_container">
                    <div class="product_wrapper touch_slider_container"><!--
                    [[product]]
                --></div>
                </div>
            </div>
        </div><!-- .body_wrapper -->
    </div>
</div>
[[+script]]
</body>
</html>