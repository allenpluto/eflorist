<!doctype html>
<html lang="en">
[[$chunk_manager_head]]
<body>
<div class="wrapper off_canvas_wrapper">
    <div class="wrapper off_canvas_container">
        <div class="off_canvas_container_mask off_canvas_halt"></div>
        [[$chunk_manager_menu]]
        [[$chunk_header]]
        <div class="wrapper body_wrapper">
            <div class="container body_container manager_body_container column_container">
                <div class="manager_product_container manager_list_item_container"><form action="[[*base]][[*control_panel]]/product/add_product" method="post"><input type="hidden" name="category_id" value="[[*category_id]]"><label id="manager_product_add_label" class="manager_list_item_add_label" for="manager_product_add_name">New Product Name</label><input type="text" id="manager_product_add_name" class="manager_list_item_add_name" name="name" value="" ><input type="submit" id="manager_product_add_submit" class="manager_list_item_add_submit manager_list_item_button general_style_input_button general_style_input_button_gray" value="Add"></form></div>
                [[product:template_name=`view_manager_product`]]
            </div>
        </div><!-- .body_wrapper -->
    </div>
</div>
[[+script]]
</body>
</html>