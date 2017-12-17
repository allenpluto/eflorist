<div class="section_container form_container">
    <div class="section_title"><h1>Edit Product</h1></div>
    <div class="section_content ajax_form_container">
        <form id="form_manager_product" class="ajax_form">
            <div class="form_row_container form_row_container_mandatory">
                <label for="form_manager_product_name">Product Name</label>
                <input id="form_manager_product_name" name="name" type="text" placeholder="Product Name" value="[[*name]]">
            </div>
            <div class="form_row_container">
                <label for="form_manager_product_alternate_name">Code Name</label>
                <input id="form_manager_product_alternate_name" name="alternate_name" type="text" placeholder="Product Code Name" value="[[*alternate_name]]">
            </div>
            <div class="form_row_container">
                <label>Image</label>
                [[image_id:object=`view_image`:template_name=`form_image_uploader`:empty_template_name=`form_image_uploader_empty`:field=`{"empty_file_uri":"./image/upload_image.jpg","field_name":"image","image_uploader_id":"form_manager_product_image"}`]]
            </div>
            <div class="form_row_container">
                <label for="form_manager_product_description">Description</label>
                <textarea id="form_manager_product_description" name="description" placeholder="Description">[[*description]]</textarea>
            </div>
            <div class="form_row_container">
                <label for="form_manager_product_price">Price</label>
                <input id="form_manager_product_price" name="price" type="text" placeholder="Price" value="[[*price]]">
            </div>
            <div class="form_row_container form_row_container_mandatory">
                <label for="form_manager_product_category">Category</label>
                <div id="form_manager_product_category" class="form_select_container">
                    <input class="form_select_result" name="category" type="hidden" value="[[*category_id]]">
                    <select class="form_select_input" placeholder="-- Select Category --"></select>
                    <div class="form_select_display_container"></div>
                </div>
            </div>
            <div class="form_row_container">
                <label for="form_manager_product_display_order">Display Order</label>
                <input id="form_manager_product_display_order" name="display_order" type="number" placeholder="Display Order" value="[[*display_order]]">
            </div>
            <div class="form_row_container">
                <label for="form_manager_product_active">Active</label>
                <input id="form_manager_product_active" name="active" type="number" placeholder="Active" value="[[*active]]" min="0" max="1">
            </div>
            <div class="form_bottom_row_container"></div>
            <div class="footer_action_wrapper"><!--
            --><a href="[[*base]]manager/product/list_product?category_id=[[*category_id]]" class="footer_action_button footer_action_button_back">Back</a><!--
            --><a href="javascript:void(0)" class="footer_action_button footer_action_button_reset">Reset</a><!--
            --><a href="javascript:void(0)" class="footer_action_button footer_action_button_save">Save</a><!--
        --></div>
            <div class="ajax_form_mask"><div class="ajax_form_mask_loading_icon"></div><div class="ajax_form_info"></div></div>
        </form>
    </div>
</div>