<div class="section_container form_container">
    <div class="section_title"><h1>Edit Page - [[*name]]</h1></div>
    <div class="section_content ajax_form_container">
        <form id="form_manager_category" class="ajax_form">
            <div class="form_row_container form_row_container_mandatory">
                <label for="form_manager_web_page_alternate_name">Page Title</label>
                <input id="form_manager_web_page_alternate_name" name="alternate_name" type="text" placeholder="Page Title" value="[[*alternate_name]]">
            </div>
            <div class="form_row_container">
                <label>Image</label>
                [[image_id:object=`view_image`:template_name=`form_image_uploader`:empty_template_name=`form_image_uploader_empty`:field=`{"empty_file_uri":"./image/upload_image.jpg","field_name":"image","image_uploader_id":"form_manager_web_page_image"}`]]
            </div>
            <div class="form_row_container">
                <label for="form_members_organization_description">Description</label>
                <textarea id="form_members_organization_description" name="description" placeholder="Description">[[*description]]</textarea>
            </div>
            <div class="form_row_container">
                <label for="form_members_organization_meta_keywords">Meta Keywords</label>
                <input id="form_members_organization_meta_keywords" name="meta_keywords" type="text" placeholder="Meta Keywords" value="[[*meta_keywords]]">
            </div>
            <div class="form_row_container">
                <label for="form_members_organization_page_title">Body Title</label>
                <input id="form_members_organization_page_title" name="page_title" type="text" placeholder="Page Title" value="[[*page_title]]">
            </div>
            <div class="form_row_container">
                <label for="form_members_organization_page_content">Body Content</label>
                <textarea id="form_members_organization_page_content" name="page_content" type="text" placeholder="Page Content">[[*page_content]]</textarea>
            </div>
            <div class="form_bottom_row_container"></div>
            <div class="footer_action_wrapper"><!--
            --><a href="[[*base]]manager/" class="footer_action_button footer_action_button_back">Back</a><!--
            --><a href="javascript:void(0)" class="footer_action_button footer_action_button_reset">Reset</a><!--
            --><a href="javascript:void(0)" class="footer_action_button footer_action_button_save">Save</a><!--
        --></div>
            <div class="ajax_form_mask"><div class="ajax_form_mask_loading_icon"></div><div class="ajax_form_info"></div></div>
        </form>
    </div>
</div>