/**
 * Created by llj on 2/09/2017.
 */

$('.manager_list_item_add_submit').click(function(event){
    var list_item_container = $(this).parents('.manager_list_item_container');
    if (!list_item_container.find('.manager_list_item_add_name').val())
    {
        list_item_container.addClass('form_row_container_highlight');
        setTimeout(function(){
            list_item_container.removeClass('form_row_container_highlight');
        },3000);
        return false;
    }
    return true;
});