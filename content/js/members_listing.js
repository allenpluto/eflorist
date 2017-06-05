/**
 * Created by User on 15/03/2017.
 */
// Click on body_wrapper, if not in any organization_block, de-select active block; if in active block, de-select active block; if in inactive block, de-select active block (if any), then set clicked block active
$('#body_wrapper').click(function(event){
    //console.log(event.target);
    if ($(event.target).parents('.members_organization_block_container').hasClass('members_organization_block_container_active'))
    {
        $(event.target).parents('.members_organization_block_container').removeClass('members_organization_block_container_active')
    }
    else
    {
        $('.members_organization_block_container_active').removeClass('members_organization_block_container_active');
        $(event.target).parents('.members_organization_block_container').addClass('members_organization_block_container_active');
    }
});