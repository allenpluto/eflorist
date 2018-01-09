<div id="form_order_container" class="section_container form_container">
    <div class="section_title"><h1>Order Form</h1></div>
    <div class="section_content ajax_form_container">
        <form id="form_order" class="ajax_form">
            <div class="form_row_container form_row_container_mandatory">
                <label for="form_order_delivery_date">Delivery Time</label>
                <select class="form_select_date_time form_select_date_time_day">
                    <option value="">Day</option>
                    <option value="1">01</option>
                    <option value="2">02</option>
                    <option value="3">03</option>
                    <option value="4">04</option>
                    <option value="5">05</option>
                    <option value="6">06</option>
                    <option value="7">07</option>
                    <option value="8">08</option>
                    <option value="9">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                    <option value="24">24</option>
                    <option value="25">25</option>
                    <option value="26">26</option>
                    <option value="27">27</option>
                    <option value="28">28</option>
                    <option value="29">29</option>
                    <option value="30">30</option>
                    <option value="31">31</option>
                </select>
                <select class="form_select_date_time form_select_date_time_month">
                    <option value="">Month</option>
                    <option value="1">Jan</option>
                    <option value="2">Feb</option>
                    <option value="3">Mar</option>
                    <option value="4">Apr</option>
                    <option value="5">May</option>
                    <option value="6">Jun</option>
                    <option value="7">Jul</option>
                    <option value="8">Aug</option>
                    <option value="9">Sep</option>
                    <option value="10">Oct</option>
                    <option value="11">Nov</option>
                    <option value="12">Dec</option>
                </select>
                <select class="form_select_date_time form_select_date_time_year">
                    <option value="2017">2017</option>
                    <option value="2018">2018</option>
                    <option value="2019">2019</option>
                </select>
                <select class="form_select_date_time form_select_date_time_hour">
                    <option value="6">06:00</option>
                    <option value="7">07:00</option>
                    <option value="8">08:00</option>
                    <option value="9">09:00</option>
                    <option value="10">10:00</option>
                    <option value="11">11:00</option>
                    <option value="12">12:00</option>
                    <option value="13">13:00</option>
                    <option value="14">14:00</option>
                    <option value="15">15:00</option>
                    <option value="16">16:00</option>
                    <option value="17">17:00</option>
                    <option value="18">18:00</option>
                    <option value="19">19:00</option>
                    <option value="20">20:00</option>
                </select>
                <p>(Please allow sufficient preparation time)</p>
            </div>
            <div class="form_row_container form_row_container_mandatory">
                <label for="form_order_recipient_name">Recipient's name</label>
                <select class="recipient_name_title">
                    <option value="Mr">Mr</option>
                    <option value="Mrs">Mrs</option>
                    <option value="Ms">Ms</option>
                </select>
                <input id="form_order_recipient_name" name="recipient_name" type="text" placeholder="Name" value="[[*recipient_name]]">
            </div>
            <div class="form_row_container">
                <label for="form_order_recipient_telephone">Tel</label>
                <input id="form_order_recipient_telephone" name="recipient_telephone" type="text" placeholder="e.g. 04xx xxx xxx, 02 xxxx xxxx" value="[[*recipient_telephone]]">
            </div>
            <div class="form_row_container form_row_container_mandatory">
                <label for="form_order_recipient_address">Address</label>
                <input id="form_order_recipient_address" name="recipient_address" type="text" placeholder="" value="[[*recipient_address]]">
            </div>
            <div class="form_row_container">
                <label for="form_order_message">Message on card</label>
                <textarea id="form_order_message" name="message" placeholder="Message">[[*message]]</textarea>
            </div>
            <div class="form_row_container">
                <label for="form_order_remarks">Remarks</label>
                <input id="form_order_remarks" name="remarks" placeholder="Remarks" value="[[*remarks]]">
            </div>

            <div class="form_row_container form_row_container_mandatory">
                <label for="form_order_sender_name">Your name</label>
                <select class="sender_name_title">
                    <option value="Mr">Mr</option>
                    <option value="Mrs">Mrs</option>
                    <option value="Ms">Ms</option>
                </select>
                <input id="form_order_sender_name" name="sender_name" type="text" placeholder="Name" value="[[*sender_name]]">
            </div>
            <div class="form_row_container form_row_container_mandatory">
                <label for="form_order_sender_email">Email</label>
                <input id="form_order_sender_email" name="sender_email" type="text" placeholder="Email" value="[[*sender_email]]">
            </div>
            <div class="form_row_container">
                <label for="form_order_sender_telephone">Tel</label>
                <input id="form_order_sender_telephone" name="sender_telephone" type="text" placeholder="e.g. 04xx xxx xxx, 02 xxxx xxxx" value="[[*sender_telephone]]">
            </div>
            <div class="form_row_container">
                <label for="form_order_sender_address">Postal Address</label>
                <input id="form_order_sender_address" name="sender_address" type="text" placeholder="" value="[[*sender_address]]">
            </div>
            <div class="form_row_container">
                <label for="form_order_sender_fax">Fax</label>
                <input id="form_order_sender_fax" name="sender_fax" type="text" placeholder="e.g. 04xx xxx xxx, 02 xxxx xxxx" value="[[*sender_fax]]">
            </div>
            <div class="form_row_container">
                <label for="form_order_credit_card_type">For credit card payment</label>
                <select id="form_order_credit_card_type">
                    <option value="Visa">Visa</option>
                    <option value="Master">Master</option>
                </select>
            </div>
            <div class="form_row_container">
                <label for="form_order_credit_card_number_1">Card numbers:</label>
                <input id="form_order_credit_card_number_1" name="credit_card_number_1" type="text">
                <input id="form_order_credit_card_number_2" name="credit_card_number_2" type="text">
                <input id="form_order_credit_card_number_3" name="credit_card_number_3" type="text">
                <input id="form_order_credit_card_number_4" name="credit_card_number_4" type="text">
            </div>
            <div class="form_row_container">
                <label for="form_order_credit_card_expire_month">Expire Date:</label>
                <input id="form_order_credit_card_expire_month" name="credit_card_expire_month" type="number" min="1" max="12" placeholder="mm">
                <span>/</span>
                <input id="form_order_credit_expire_year" name="credit_card_expire_year" type="number" min="[[*current_year]]" placeholder="yyyy">
            </div>
            <div class="form_row_container">
                <p>** We will confirm your order with total billing amount before delivery by phone/email.  A photo of your purchase will be sent to you via email/fax/post if we are the executing shop.  If payment is made by credit card, for security reason we will ask for the ccv number during confirmation. Thank you for your order.</p>
            </div>
            <div class="form_row_container form_row_container_mandatory">
                <div id="form_order_recaptcha" class="g-recaptcha" data-sitekey="6LdJNS8UAAAAAJJMsqWWT5CW23qAvvnSQf1v7mU2"></div>
            </div>
            <div class="form_row_container form_row_button_container">
                <a id="form_order_submit" href="javascript:void(0)" class="general_style_input_button general_style_input_button_gray">Submit</a>
                <a id="form_order_reset" href="javascript:void(0)" class="general_style_input_button general_style_input_button_gray">Reset</a>
            </div>
            <div class="ajax_form_mask"><div class="ajax_form_mask_loading_icon"></div><div class="ajax_form_info"></div></div>
        </form>
    </div>
</div>