/**
 * Created by User on 26/05/2017.
 */

//$('body').on('click',function(event){
//    var click_element = $(event.target);
//    if (click_element.parents().hasClass('listing_detail_view_gallery_container'))
//    {
//        var current_gallery_container = click_element.closest('.listing_detail_view_gallery_container');
//        if (!current_gallery_container.closest('.listing_detail_view_gallery_wrapper').hasClass('listing_detail_view_gallery_wrapper_active'))
//        {
//            $('.listing_detail_view_gallery_wrapper_active').removeClass('listing_detail_view_gallery_wrapper_active');
//            current_gallery_container.closest('.listing_detail_view_gallery_wrapper').addClass('listing_detail_view_gallery_wrapper_active');
//        }
//        if (!current_gallery_container.hasClass('listing_detail_view_gallery_container_active'))
//        {
//            $('.listing_detail_view_gallery_container_active').removeClass('listing_detail_view_gallery_container_active');
//            current_gallery_container.addClass('listing_detail_view_gallery_container_active');
//        }
//    }
//    else
//    {
//        $('.listing_detail_view_gallery_wrapper_active').removeClass('listing_detail_view_gallery_wrapper_active');
//        $('.listing_detail_view_gallery_container_active').removeClass('listing_detail_view_gallery_container_active');
//    }
//});

$(document).ready(function(){
    $('.listing_detail_view_gallery_content').append('<div class="touch_slider_navigation_button touch_slider_navigation_button_zoom"></div>');
    $('.listing_detail_view_gallery_content').on('click','.touch_slider_navigation_button_zoom', function (event) {
        var current_gallery_container = $(this).closest('.listing_detail_view_gallery_container');
        if (!current_gallery_container.hasClass('listing_detail_view_gallery_container_active'))
        {
            current_gallery_container.closest('.listing_detail_view_gallery_wrapper').addClass('listing_detail_view_gallery_wrapper_active');
            current_gallery_container.closest('.listing_detail_view_gallery_wrapper').find('.listing_detail_view_gallery_container_active').removeClass('listing_detail_view_gallery_container_active');
            current_gallery_container.addClass('listing_detail_view_gallery_container_active');
        }
        else
        {
            current_gallery_container.closest('.listing_detail_view_gallery_wrapper').removeClass('listing_detail_view_gallery_wrapper_active');
            current_gallery_container.closest('.listing_detail_view_gallery_wrapper').find('.listing_detail_view_gallery_container_active').removeClass('listing_detail_view_gallery_container_active');
        }
    });
});