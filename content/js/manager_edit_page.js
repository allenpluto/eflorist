/**
 * Created by llj on 10/08/2017.
 */

$('.footer_action_button_reset').click(function(event){
    event.preventDefault();
    $(this).closest('.ajax_form_container').trigger('retrieve_form_data');
});

$('.footer_action_button_save').click(function(event){
    event.preventDefault();
    $(this).closest('.ajax_form_container').trigger('post_form_data');
});
