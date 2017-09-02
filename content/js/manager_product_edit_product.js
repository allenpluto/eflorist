/**
 * Created by llj on 3/09/2017.
 */

$('#form_manager_product_category .form_select_input').change(function(){
    var category_selected = $(this).val();

    var back_link = $('.footer_action_button_back').attr('href').split('?')[0];
    if (category_selected)
    {
        back_link += '?category_id='+category_selected;
    }

    $('.footer_action_button_back').attr('href',back_link);
});