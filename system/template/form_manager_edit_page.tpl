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
<div class="section_container container form_container">
    <div class="section_title"><h1>Edit My Business</h1></div>
    <div class="section_content ajax_form_container">
        <form id="form_members_organization" class="ajax_form">
            <div class="form_row_container form_row_container_mandatory">
                <label for="form_members_organization_name">Business Name</label>
                <input id="form_members_organization_name" name="name" type="text" placeholder="Business Name" value="[[*name]]">
            </div>
            <div class="form_row_container form_row_organization_logo_container form_row_container_mandatory">
                <label>Logo</label>
                [[logo_id:object=`view_image`:template_name=`form_image_uploader`:empty_template_name=`form_image_uploader_empty`:field=`{"empty_file_uri":"./image/upload_logo.jpg","field_name":"logo","image_uploader_id":"form_members_organization_logo"}`]]
            </div>
            <div class="form_row_container form_row_organization_banner_container">
                <label>Banner</label>
                [[banner_id:object=`view_image`:template_name=`form_image_uploader`:empty_template_name=`form_image_uploader_empty`:field=`{"empty_file_uri":"./image/upload_banner.jpg","field_name":"banner","image_uploader_id":"form_members_organization_banner"}`]]
            </div>
            <div class="form_row_container form_row_street_address_container form_row_container_mandatory">
                <input id="form_members_organization_street_address_place_id" name="place_id" type="hidden" value="[[*place_id]]">
                <label for="form_members_organization_street_address">Street Address</label>
                <input id="form_members_organization_street_address" class="form_row_street_address_input" type="text">
                <div class="form_row_street_address_display_container"></div>
                <div id="form_members_organization_street_address_map" class="form_row_street_address_map"></div>
            </div>
            <div class="form_row_container">
                <label for="form_members_organization_category">Category</label>
                <div id="form_members_organization_category" class="form_select_container">
                    <input class="form_select_result" name="category" type="hidden" placeholder="Category" value="[[*category]]">
                    <select class="form_select_input"></select>
                    <div class="form_select_display_container"></div>
                </div>
            </div>
            <div class="form_row_container form_row_container_phone">
                <label for="form_members_organization_telephone">Main Phone</label>
                <input id="form_members_organization_telephone" name="telephone" type="text" placeholder="Main Phone" value="[[*telephone]]">
            </div>
            <div class="form_row_container form_row_container_phone">
                <label for="form_members_organization_alternate_telephone">Alternate Phone</label>
                <input id="form_members_organization_alternate_telephone" name="alternate_telephone" type="text" placeholder="Alternate Phone" value="[[*alternate_telephone]]">
            </div>
            <div class="form_row_container form_row_container_phone">
                <label for="form_members_organization_mobile">Mobile Phone</label>
                <input id="form_members_organization_mobile" name="mobile" type="text" placeholder="Mobile Phone" value="[[*mobile]]">
            </div>
            <div class="form_row_container form_row_container_phone">
                <label for="form_members_organization_fax_number">Fax</label>
                <input id="form_members_organization_fax_number" name="fax_number" type="text" placeholder="Fax" value="[[*fax_number]]">
            </div>
            <div class="form_row_container form_row_container_email">
                <label for="form_members_organization_email">Email Address</label>
                <input id="form_members_organization_email" name="email" type="text" placeholder="Email Address" value="[[*email]]">
            </div>
            <div class="form_row_container form_row_container_uri">
                <label for="form_members_organization_website_uri">Website</label>
                <input id="form_members_organization_website_uri" name="website_uri" type="text" placeholder="Website" value="[[*website_uri]]">
            </div>
            <div class="form_row_container">
                <label for="form_members_organization_facebook">Facebook</label>
                <input id="form_members_organization_facebook" name="facebook_link" type="text" placeholder="Facebook" value="[[*facebook_link]]">
            </div>
            <div class="form_row_container">
                <label for="form_members_organization_googleplus">Google+</label>
                <input id="form_members_organization_googleplus" name="googleplus_link" type="text" placeholder="Google+" value="[[*googleplus_link]]">
            </div>
            <div class="form_row_container">
                <label for="form_members_organization_linkedin">LinkedIn</label>
                <input id="form_members_organization_linkedin" name="linkedin_link" type="text" placeholder="LinkedIn" value="[[*linkedin_link]]">
            </div>
            <div class="form_row_container">
                <label for="form_members_organization_pinterest">Pinterest</label>
                <input id="form_members_organization_pinterest" name="pinterest_link" type="text" placeholder="Pinterest" value="[[*pinterest_link]]">
            </div>
            <div class="form_row_container">
                <label for="form_members_organization_blog">Blog</label>
                <input id="form_members_organization_blog" name="blog_link" type="text" placeholder="Blog" value="[[*blog_link]]">
            </div>
            <div class="form_row_container">
                <label for="form_members_organization_twitter">Twitter</label>
                <input id="form_members_organization_twitter" name="twitter_link" type="text" placeholder="Twitter" value="[[*twitter_link]]">
            </div>
            <div class="form_row_container">
                <label>Opening Hours</label>
                <div class="form_hours_work_container">
                    <input class="form_hours_work_result" type="hidden" name="hours_work" value="[[*hours_work]]">
                    <div class="form_hours_work_input">
                        <div class="form_hours_work_input_label">Set Time Period</div>
                        <select class="form_hours_work_input_time">
                            <option value="[[0.375,0.7083333333]]">9 to 5 (9:00-17:00)</option>
                            <option value="[[0.375,0.5000000000],[0.5416666667,0.7083333333]]">9:00-12:00, 13:00-17:00</option>
                            <option value="[[0.2916666667,0.7916666667]]">7 to 7 (7:00-19:00)</option>
                            <option value="[[0,1]]">24 Hours (0:00-23:59)</option>
                            <option value="closed">Closed</option>
                            <option value="custom">Customise</option>
                        </select>
                        <div class="form_hours_work_input_time_custom_container">
                            <input class="form_hours_work_input_time_custom_result" type="hidden" value="[[0.375,0.708333333333]]">
                            <div class="form_hours_work_input_label">Set Customised Time Period</div>
                            <select class="form_hours_work_input_time_custom_time_period_count">
                                <option value="1" selected>One set of time in one day</option>
                                <option value="2">Two sets of time in one day</option>
                                <option value="3">Three sets of time in one day</option>
                            </select>
                            <div class="form_hours_work_input_time_custom_time_period_container"></div>
                        </div>
                        <div class="form_hours_work_input_label">To Weekday</div>
                        <select class="form_hours_work_input_weekday">
                            <option value="1,2,3,4,5">Mon to Fri</option>
                            <option value="6,0">Sat and Sun</option>
                            <option value="1,2,3,4,5,6,0">All Week</option>
                            <option value="1">Monday</option>
                            <option value="2">Tuesday</option>
                            <option value="3">Wednesday</option>
                            <option value="4">Thursday</option>
                            <option value="5">Friday</option>
                            <option value="6">Saturday</option>
                            <option value="0">Sunday</option>
                        </select>
                        <div class="form_hours_work_input_button_container">
                            <input class="form_hours_work_input_submit general_style_input_button general_style_input_button_gray" type="button" value="Set">
                            <input class="form_hours_work_input_cancel general_style_input_button general_style_input_button_gray" type="button" value="Cancel">
                        </div>
                    </div>
                    <div class="form_hours_work_display"></div>
                </div>
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