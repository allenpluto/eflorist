<div id="form_inquiry_container" class="section_container form_container">
    <div class="section_title"><h1>Send Inquiry</h1></div>
    <div class="section_content ajax_form_container">
        <form id="form_inquiry" class="ajax_form">
            <div class="form_row_container form_row_container_mandatory">
                <label for="form_inquiry_client_name">Name</label>
                <input id="form_inquiry_client_name" name="client_name" type="text" placeholder="Name" value="[[*client_name]]">
            </div>
            <div class="form_row_container form_row_container_mandatory">
                <label for="form_inquiry_client_email">Email</label>
                <input id="form_inquiry_client_email" name="client_email" type="text" placeholder="Email" value="[[*client_email]]">
            </div>
            <div class="form_row_container">
                <label for="form_inquiry_client_telephone">Tel</label>
                <input id="form_inquiry_client_telephone" name="client_telephone" type="text" placeholder="Telephone" value="[[*client_telephone]]">
            </div>
            <div class="form_row_container form_row_container_mandatory">
                <label for="form_inquiry_client_message">Message</label>
                <textarea id="form_inquiry_client_message" name="client_message" placeholder="Message">[[*client_message]]</textarea>
            </div>
            <div class="form_row_container form_row_container_mandatory">
                <div id="form_inquiry_recaptcha" class="g-recaptcha" data-sitekey="6LdJNS8UAAAAAJJMsqWWT5CW23qAvvnSQf1v7mU2"></div>
            </div>
            <div class="form_row_container form_row_button_container">
                <a id="form_inquiry_submit" href="javascript:void(0)" class="general_style_input_button general_style_input_button_gray">Submit</a>
            </div>
            <div class="ajax_form_mask"><div class="ajax_form_mask_loading_icon"></div><div class="ajax_form_info"></div></div>
        </form>
    </div>
</div>