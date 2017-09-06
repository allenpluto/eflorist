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
            <div id="home_body_container" class="container body_container column_container">
                <div class="column column_12 column_m_6 column_l_6 column_xl_4 column_xxl_4">[[image]]</div>
                <div class="column column_12 column_m_6 column_l_6 column_xl_8 column_xxl_8 column_container"><div class="column column_12 column_xl_6 column_xxl_6 page_content_container">[[*page_content]]</div><div class="column column_12 column_xl_6 column_xxl_6">[[$form_inquiry]]</div></div>
            </div>
        </div><!-- .body_wrapper -->
    </div>
</div>
[[+script]]
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>
</html>