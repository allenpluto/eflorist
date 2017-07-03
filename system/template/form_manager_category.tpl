<div class="section_container container form_container">
    <div class="section_title"><h1>Edit Category</h1></div>
    <div class="section_content ajax_form_container">
        <form id="form_manager_category" class="ajax_form">
            <div class="form_row_container form_row_container_mandatory">
                <label for="form_manager_category_name">Category Name</label>
                <input id="form_manager_category_name" name="name" type="text" placeholder="Business Name" value="[[*name]]">
            </div>
            <div class="form_row_container form_row_organization_logo_container form_row_container_mandatory">
                <label>Image</label>
                [[image_id:object=`view_image`:template_name=`form_image_uploader`:empty_template_name=`form_image_uploader_empty`:field=`{"empty_file_uri":"./image/upload_image.jpg","field_name":"image","image_uploader_id":"form_manager_category_logo"}`]]
            </div>
            <div class="form_bottom_row_container"></div>
            <div class="footer_action_wrapper"><!--
            --><a href="[[*base]]members/listing/" class="footer_action_button footer_action_button_back">Back</a><!--
            --><a href="javascript:void(0)" class="footer_action_button footer_action_button_reset">Reset</a><!--
            --><a href="javascript:void(0)" class="footer_action_button footer_action_button_save">Save</a><!--
        --></div>
            <div class="ajax_form_mask"><div class="ajax_form_mask_loading_icon"></div><div class="ajax_form_info"></div></div>
        </form>
    </div>
</div>